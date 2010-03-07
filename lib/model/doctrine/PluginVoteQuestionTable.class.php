<?php
/**
 */
class PluginVoteQuestionTable extends Doctrine_Table
{
  public function getListPager($page = 1, $size = 20, $memberId =null)
  {
    $query = $this->createQuery()
            ->orderBy('updated_at DESC');

    if ($memberId)
    {
      $query->where('member_id = ?', $memberId);
    }
    
    $pager = new sfDoctrinePager('VoteQuestion', $size);
    $pager->setQuery($query);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }
}