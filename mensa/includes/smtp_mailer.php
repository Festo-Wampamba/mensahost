<?php
/**
 * Minimal pure-PHP SMTP mailer — connects directly to Postfix on 127.0.0.1:25.
 * No sendmail binary required. No external libraries.
 */
class SmtpMailer
{
    private string  $host;
    private int     $port;
    private string  $from;
    private string  $fromName;
    private string  $helo;
    private ?string $lastError = null;

    public function __construct(
        string $from,
        string $fromName = '',
        string $host     = '127.0.0.1',
        int    $port     = 25,
        string $helo     = 'mensahost.tech'
    ) {
        $this->from     = $from;
        $this->fromName = $fromName;
        $this->host     = $host;
        $this->port     = $port;
        $this->helo     = $helo;
    }

    public function send(string $toEmail, string $toName, string $subject, string $body): bool
    {
        $this->lastError = null;

        $sock = @fsockopen($this->host, $this->port, $errno, $errstr, 5);
        if ($sock === false) {
            $this->fail("Connect to {$this->host}:{$this->port} failed: {$errstr} ({$errno})");
            return false;
        }

        stream_set_timeout($sock, 10);

        if (!$this->expect($sock, 220, 'greeting'))   return $this->abort($sock);

        fwrite($sock, "EHLO {$this->helo}\r\n");
        if (!$this->expect($sock, 250, 'EHLO'))        return $this->abort($sock);

        fwrite($sock, "MAIL FROM:<{$this->from}>\r\n");
        if (!$this->expect($sock, 250, 'MAIL FROM'))   return $this->abort($sock);

        fwrite($sock, "RCPT TO:<{$toEmail}>\r\n");
        if (!$this->expect($sock, 250, 'RCPT TO'))     return $this->abort($sock);

        fwrite($sock, "DATA\r\n");
        if (!$this->expect($sock, 354, 'DATA'))        return $this->abort($sock);

        $fromFmt = $this->fromName
            ? $this->encodeHeader($this->fromName) . " <{$this->from}>"
            : $this->from;
        $toFmt = $toName
            ? $this->encodeHeader($toName) . " <{$toEmail}>"
            : $toEmail;

        $msgId = '<' . time() . '.' . bin2hex(random_bytes(8)) . '@mensahost.tech>';

        $headers = "Date: "        . date('r')                         . "\r\n"
                 . "From: "       . $fromFmt                           . "\r\n"
                 . "To: "         . $toFmt                             . "\r\n"
                 . "Reply-To: "   . $this->from                        . "\r\n"
                 . "Subject: "    . $this->encodeHeader($subject)      . "\r\n"
                 . "Message-ID: " . $msgId                             . "\r\n"
                 . "MIME-Version: 1.0\r\n"
                 . "Content-Type: text/plain; charset=UTF-8\r\n"
                 . "Content-Transfer-Encoding: 8bit\r\n"
                 . "X-Mailer: MensaHost/1.0\r\n";

        fwrite($sock, $headers . "\r\n" . $this->dotStuff($body) . "\r\n.\r\n");

        if (!$this->expect($sock, 250, 'end-of-data')) return $this->abort($sock);

        fwrite($sock, "QUIT\r\n");
        fclose($sock);
        return true;
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    /* ── private helpers ── */

    private function expect($sock, int $code, string $stage): bool
    {
        $response = '';
        while (!feof($sock)) {
            $line = fgets($sock, 512);
            if ($line === false) break;
            $response .= $line;
            // Multi-line responses: "250-text" continues, "250 text" ends
            if (strlen($line) >= 4 && $line[3] === ' ') break;
        }
        $got = (int) substr($response, 0, 3);
        if ($got !== $code) {
            $this->fail("SMTP [{$stage}]: expected {$code}, got: " . trim($response));
            return false;
        }
        return true;
    }

    private function abort($sock): bool
    {
        @fwrite($sock, "QUIT\r\n");
        @fclose($sock);
        return false;
    }

    private function fail(string $msg): void
    {
        $this->lastError = $msg;
        error_log('[SmtpMailer] ' . $msg);
    }

    private function dotStuff(string $body): string
    {
        $body  = str_replace(["\r\n", "\r"], "\n", $body);
        $lines = explode("\n", $body);
        foreach ($lines as &$line) {
            if (isset($line[0]) && $line[0] === '.') {
                $line = '.' . $line;
            }
        }
        return implode("\r\n", $lines);
    }

    private function encodeHeader(string $value): string
    {
        if (!preg_match('/[^\x20-\x7E]/', $value)) {
            return $value;
        }
        return '=?UTF-8?B?' . base64_encode($value) . '?=';
    }
}
