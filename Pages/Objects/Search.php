<?php

namespace Oro\Bundle\TestFrameworkBundle\Pages\Objects;

use Oro\Bundle\TestFrameworkBundle\Pages\Page;

class Search extends Page
{
    protected $simpleSearch;
    protected $searchButton;
    protected $pane;

    public function __construct($testCase)
    {
        parent::__construct($testCase);
        $this->pane = $this->byXPath('//span[@title="Search"]');
        $this->simpleSearch = $this->byId('search-bar-search');
        $this->searchButton = $this->byXPath("//form[@id='top-search-form']//div/button[contains(.,'Go')]");
    }

    /**
     * @param string $value
     * @return $this
     */
    public function search($value)
    {
        if (!$this->simpleSearch->displayed()) {
            $this->pane->click();
        }
        $this->simpleSearch->clear();
        $this->simpleSearch->value($value);
        $this->waitForAjax();
        return $this;
    }

    /**
     * @param null|string $filter
     * @return mixed
     */
    public function suggestions($filter = null)
    {
        if (!is_null($filter)) {
            $result = $this->elements($this->using("xpath")->value("//div[@class='header-search-frame']//a[contains(., '{$filter}')]"));
        } else {
            $result = $this->elements($this->using("xpath")->value("//div[@id='search-dropdown']/ul/li/a"));
        }

        return $result;
    }

    /**
     * @param string $filter
     * @return $this
     * @throws \Exception
     */
    public function select($filter)
    {
        $found = current($this->suggestions($filter));
        $this->test->moveto($found);
        $found->click();

        //$this->byXpath("//div[@id='search-dropdown']/ul/li/a[contains(.,'{$filter}')]")->click();
        sleep(1);
        $this->waitForAjax();
        $this->waitPageToLoad();
        $this->waitForAjax();
        if ($this->isElementPresent("//div[@class='container-fluid search-header clearfix'][contains(., 'Search')]") ||
            $this->isElementPresent("//div[@class='container-fluid search-header clearfix'][contains(., 'Records tagged as')]")) {
            return $this;
        } else {
            throw new \Exception("No search or tag result page opened");
        }
    }

    /**
     * @param string $filter
     * @return mixed
     */
    public function result($filter)
    {
        if (!is_null($filter)) {
            $result = $this->elements($this->using("xpath")->value("//div[@id='search-result-grid']//tr//h1/a[contains(., '{$filter}')]"));
        } else {
            $result = $this->elements($this->using("xpath")->value("//div[@id='search-result-grid']//tr//h1/a"));
        }

        return $result;
    }

    public function submit()
    {
        $this->searchButton->click();
        $this->waitPageToLoad();
        $this->waitForAjax();
        return $this;
    }

    /**
     * @param string $entitytype
     * @param string $entitycount
     * @return $this
     */
    public function assertEntity($entitytype, $entitycount)
    {
        $this->assertElementPresent("//td[@class='search-entity-types-column']//a[contains(., '{$entitytype} ({$entitycount})')]");

        return $this;
    }

}
