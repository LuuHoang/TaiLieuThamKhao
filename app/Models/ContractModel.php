<?php


namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class ContractModel extends AbstractModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contracts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sample_contract_id','company_id','represent_company_hire','phone_company_hire','gender_hire','name_company_rental','address_company_rental','represent_company_rental','gender_rental','phone_number_rental','service_package_id','type_service_package','extend_package_id','tax','total_price','payment_status','effective_date','end_date','cancellation_notice_deadline','deposit_money','payment_term_all','employee_represent','contract_status','date_signed','created_by','contract_code'];

    /**
     * @return hasOne
     */
    public function company()
    {
        return $this->hasOne('App\Models\CompanyModel', 'id', 'company_id');
    }

    /**
     * @return hasOne
     */
    public function sampleContract()
    {
        return $this->hasOne('App\Models\SampleContractModel', 'id', 'sample_contract_id');
    }
    /**
     * @return hasOne
     */
    public function servicePackage()
    {
        return $this->hasOne('App\Models\ServicePackageModel', 'id','service_package_id');
    }
    /**
     * @return hasOne
     */
    public function extendPackage()
    {
        return $this->hasOne('App\Models\ExtendPackageModel', 'id','extend_package_id');
    }
    /**
     * @return hasOne
     */
    public function admin()
    {
        return $this->hasOne('App\Models\AdminModel', 'id','employee_represent');
    }
    /**
     * @return belongsToMany
     */
    public function sampleContractProperty()
    {
        return $this->belongsToMany('App\Models\SampleContractPropertyModel','contract_contents','contract_id','sample_contract_property_id')->withPivot(['content', 'id']);
    }
}
