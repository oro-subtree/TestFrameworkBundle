<?php

namespace Oro\Bundle\TestFrameworkBundle\Pages\Objects;

use Oro\Bundle\TestFrameworkBundle\Pages\Entity;
use Oro\Bundle\TestFrameworkBundle\Pages\Page;

class Account extends Page implements Entity
{
    protected $accountname;
    protected $street;
    protected $city;
    protected $zipcode;
    protected $country;
    protected $state;

    public function __construct($testCase, $redirect = true)
    {
        parent::__construct($testCase, $redirect);
    }

    public function init()
    {
        $this->accountname = $this->byId('orocrm_account_form_name');
        $this->street = $this->byId('orocrm_account_form_values_4_address_street');
        $this->city = $this->byId('orocrm_account_form_values_4_address_city');
        $this->country = $this->byXpath("//div[@id='s2id_orocrm_account_form_values_4_address_country']/a");
        $this->state = $this->byXpath("//div[@id='s2id_orocrm_account_form_values_4_address_state']/a");
        $this->zipcode = $this->byId('orocrm_account_form_values_4_address_postalCode');

        return $this;
    }

    public function setAccountName($accountname)
    {
        $this->accountname->clear();
        $this->accountname->value($accountname);
        return $this;
    }

    public function getAccountName()
    {
        return $this->accountname->value();
    }

    public function setStreet($street)
    {
        $this->street->clear();
        $this->street->value($street);
        return $this;
    }

    public function getStreet()
    {
        return $this->street->value();
    }

    public function setCity($city)
    {
        $this->city->clear();
        $this->city->value($city);
        return $this;
    }

    public function getCity()
    {
        return $this->city->value();
    }

    public function setCountry($country)
    {
        $this->country->click();
        $this->waitForAjax();
        $this->byXpath("//div[@id='select2-drop']/div/input")->value($country);
        $this->waitForAjax();
        $this->assertElementPresent("//div[@id='select2-drop']//div[contains(., '{$country}')]", "Country's autocoplete doesn't return search value");
        $this->byXpath("//div[@id='select2-drop']//div[contains(., '{$country}')]")->click();

        return $this;
    }

    public function setState($state)
    {
        $this->state->click();
        $this->waitForAjax();
        $this->byXpath("//div[@id='select2-drop']/div/input")->value($state);
        $this->waitForAjax();
        $this->assertElementPresent("//div[@id='select2-drop']//div[contains(., '{$state}')]", "Country's autocoplete doesn't return search value");
        $this->byXpath("//div[@id='select2-drop']//div[contains(., '{$state}')]")->click();

        return $this;
    }

    public function setZipCode($zipcode)
    {
        $this->zipcode->clear();
        $this->zipcode->value($zipcode);
        return $this;
    }

    public function getZipCode()
    {
        return $this->zipcode->value();
    }

    public function save()
    {
        $this->byXPath("//button[contains(., 'Save')]")->click();
        $this->waitPageToLoad();
        $this->waitForAjax();
        return $this;
    }

    public function close()
    {
        return new Accounts($this->test);
    }

    public function edit()
    {
        $this->byXPath("//div[@class='pull-left btn-group icons-holder']/a[@title = 'Edit account']")->click();
        $this->waitPageToLoad();
        $this->waitForAjax();
        $this->init();
        return $this;
    }

    public function delete()
    {
        $this->byXPath("//div[@class='pull-left btn-group icons-holder']/a[contains(., 'Delete')]")->click();
        $this->byXPath("//div[div[contains(., 'Delete Confirmation')]]//a[text()='Yes, Delete']")->click();
        $this->waitPageToLoad();
        $this->waitForAjax();
        return new Accounts($this->test, false);
    }
}
