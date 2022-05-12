<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Service;

use Ramsey\Uuid\Uuid;
use Twig\Environment;
use DateTimeImmutable;
use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use PHPUnit\Framework\TestCase;
use App\Auth\Service\JoinConfirmationSender;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MimeEmail;

/**
 * @covers \App\Auth\Service\JoinConfirmationSender
 *
 * @internal
 */
final class JoinConfirmationSenderTest extends TestCase
{
    public function testSuccess(): void
    {
        $to = new Email('user@app.test');
        $token = new Token(Uuid::uuid4()->toString(), new DateTimeImmutable());
        $confirmUrl = 'http://test/join/confirm?token=' . $token->getValue();

        $twig = $this->createMock(Environment::class);
        $twig->expects(self::once())->method('render')->with(
            self::equalTo('auth/join/confirm.html.twig'),
            self::equalTo(['token' => $token]),
        )->willReturn($body = '<a href="' . $confirmUrl . '">' . $confirmUrl . '</a>');

        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects(self::once())->method('send')
            ->willReturnCallback(static function (MimeEmail $message) use ($to, $body): void {
                self::assertEquals($to->getValue(), $message->getTo()[0]->getAddress());
                self::assertEquals('Join Confirmation', $message->getSubject());
                self::assertEquals($body, $message->getHtmlBody());
            });

        $sender = new JoinConfirmationSender($mailer, $twig);

        $sender->send($to, $token);
    }
}
