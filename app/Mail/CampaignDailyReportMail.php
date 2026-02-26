<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignDailyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    /** @var array<string,mixed> */
    public array $summary;

    /**
     * @param  array<string,mixed>  $summary
     */
    public function __construct(User $user, array $summary)
    {
        $this->user = $user;
        $this->summary = $summary;
    }

    public function build(): self
    {
        return $this
            ->subject('Báo cáo hiệu suất chiến dịch ' . $this->summary['from']->timezone(config('app.timezone'))->format('d/m/Y H:i') . ' - ' . $this->summary['to']->timezone(config('app.timezone'))->format('d/m/Y H:i'))
            ->view('emails.campaign-daily-report');
    }
}

