<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LandingIssueMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    /** @var array<int,array<string,mixed>> */
    public array $issues;

    /**
     * @param  array<int,array<string,mixed>>  $issues
     */
    public function __construct(User $user, array $issues)
    {
        $this->user = $user;
        $this->issues = $issues;
    }

    public function build(): self
    {
        return $this
            ->subject('Cảnh báo landing/coupon đang gặp lỗi')
            ->view('emails.landing-issues');
    }
}

