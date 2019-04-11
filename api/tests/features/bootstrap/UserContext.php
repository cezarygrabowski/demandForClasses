<?php


use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;

class UserContext implements Context
{
    /**
     * @Given There is a user :arg1
     */
    public function thereIsAUser($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given user :arg1 has following qualifications:
     */
    public function userHasFollowingQualifications($arg1, TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @When I request profile informations of :arg1
     */
    public function iRequestProfileInformationsOf($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should see following qualifications:
     */
    public function iShouldSeeFollowingQualifications(TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @Given user :arg1 has :arg2 undistributed hours in :arg3 week
     */
    public function userHasUndistributedHoursInWeek($arg1, $arg2, $arg3)
    {
        throw new PendingException();
    }

    /**
     * @Then I should see that user has :arg1 undistributed hours in :arg2 week
     */
    public function iShouldSeeThatUserHasUndistributedHoursInWeek($arg1, $arg2)
    {
        throw new PendingException();
    }
}
