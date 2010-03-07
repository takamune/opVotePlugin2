<?php
class voteActions extends opVotePluginVoteActions
{
    public function executeEdit(sfWebRequest $request) {
	  parent::executeEdit($request);
    }

    public function executeNew(sfWebRequest $request) {
	  parent::executeNew($request);
    }
}
