<?php


namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContractExtension extends Mailable
{
    use Queueable, SerializesModels;
    private $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('契約更新のお知らせ（アルバムメーカー）※自動メール')->markdown('emails.contract_extension')->with([
            'id' => $this->data['id'],
            'company' => $this->data['company'],
            'end_date' => $this->data['end_date'],
            'employee' => $this->data['employee'],
            'phone_company_hire' => $this->data['phone_company_hire'],
            'represent_company_hire' => $this->data['represent_company_hire'],
        ]);
    }
}
