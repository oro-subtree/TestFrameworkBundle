<?php

namespace Oro\Bundle\TestFrameworkBundle\Pages\Objects;

use Oro\Bundle\TestFrameworkBundle\Pages\PageFilteredGrid;

class Users extends PageFilteredGrid
{
    const URL = 'user';

    public function __construct($testCase, $redirect = true)
    {
        $this->redirectUrl = self::URL;
        parent::__construct($testCase, $redirect);

    }

    public function add()
    {
        $this->test->byXPath("//div[@class = 'container-fluid']//a[contains(., 'Create user')]")->click();
        //due to bug BAP-965
        sleep(1);
        $this->waitPageToLoad();
        $this->waitForAjax();
        $user = new User($this->test);
        return $user->init(true);
    }

    public function open($entityData = array())
    {
        $user = $this->getEntity($entityData);
        $user->click();
        $this->waitPageToLoad();
        $this->waitForAjax();

        return new User($this->test);
    }
}
