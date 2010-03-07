<?php

class opVotePluginVoteActions extends sfActions
{
    public function executeIndex(sfWebRequest $request)
    {
        $limit = 10;
        $this->pager = Doctrine::getTable('VoteQuestion')->getListPager($request->getParameter('page'), $limit);
    }

    public function executeNew(sfWebRequest $request)
    {
        $this->form = new VoteQuestionForm();
    }

    public function executeCreate(sfWebRequest $request)
    {
        $object = new VoteQuestion();
        $object->setMember($this->getUser()->getMember());
        $this->form = new VoteQuestionForm($object);

        $requestVoteQuestion = $request->getParameter('vote_question');
        $this->forward404Unless($this->getUser()->getMemberId() == $requestVoteQuestion['member_id']);
        if ($this->form->bindAndSave($request->getParameter('vote_question')))
        {
            $this->redirect('@vote_list');
        }
        // newのテンプレートを使う
        $this->setTemplate('new');
    }


    public function executeEdit(sfWebRequest $request)
    {
        $object = $this->getRoute()->getObject();
        $this-> forward404Unless($this->getUser()->getMemberId() == $object->getMemberId());
        $this->form = new VoteQuestionForm($object);

    }

    public function executeUpdate(sfWebRequest $request)
    {
        $object = $this->getRoute()->getObject();
        $this-> forward404Unless($this->getUser()->getMemberId() == $object->getMemberId());
        $this->form = new VoteQuestionForm($object);
        if ($this->form->bindAndSave($request->getParameter('vote_question')))
        {
            $this->getUser()->setFlash('notice', '編集しました');
            $this->redirect('@vote_list');
        }
        $this->setTemplate('edit');
    }

    public function executeShow(sfWebRequest $request)
    {
        $this->question = $this->getRoute()->getObject();
        $yourAnswer = Doctrine::getTable('VoteAnswer')->findOneByMemberIdAndVoteQuestionId(
                $this->getUser()->getMemberId(),
                $this->question->getId()
        );
        if($yourAnswer || $this->question->getMemberId() == $this->getUser()->getMemberId())
        {
            // 結果出力のためのデータ集計
            $answers = Doctrine::getTable('VoteAnswer')->findByVoteQuestionId($this->question->getId());
            $options = Doctrine::getTable('VoteQuestionOption')->findByVoteQuestionId($this->question->getId());
            $this->options = $options->toKeyValueArray('id', 'body');

            $this->answerTotal = array();
            $this->total = 0;
            foreach ($answers as $answer)
            {
                $this->total++;
                if (isset($this->answerTotal[$answer->getVoteQuestionOptionId()]))
                {
                    $this->answerTotal[$answer->getVoteQuestionOptionId()]++;
                } else
                {
                    $this->answerTotal[$answer->getVoteQuestionOptionId()] = 1;
                }
            }
            arsort($this->answerTotal);
        }
        else
        {
            //　回答済みでないかつ作成者でないときフォームオブジェクト作成
            $voteAnswer = new VoteAnswer();
            $voteAnswer->setVoteQuestion($this->question);
            $voteAnswer->setMember($this->getUser()->getMember());
            $this->form = new VoteAnswerForm($voteAnswer);
        }
    }

    public function executePost(sfWebRequest $request)
    {
        $question = $this->getRoute()->getObject();

        $yourAnswer = Doctrine::getTable('VoteAnswer')->findOneByMemberIdAndVoteQuestionId(
                $this->getUser()->getMemberId(),
                $question->getId()
        );

        // 回答済みであったり、作者であった場合は４０４
        $this->forward404If($yourAnswer || $question->getMemberId() == $this->getUser()->getMemberId());

        $voteAnswer = new VoteAnswer();
        $voteAnswer->setVoteQuestion($question);
        $voteAnswer->setMember($this->getUser()->getMember());

        $this->form = new VoteAnswerForm($voteAnswer);
        if ($this->form->bindAndSave($request->getParameter('vote_answer')))
        {
            $this->redirect('@vote_show?id'.$request->getId());
        }

        // 保存失敗のときは、show のテンプレートを使いまわし
        $this->setTemplate('show');
    }
}
