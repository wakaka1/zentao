<?php
/**
 * The edit view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: edit.html.php 4897 2013-06-26 01:13:16Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('oldStoryID', $task->story); ?>
<?php js::set('confirmChangeProject', $lang->sprint->confirmChangeProject); ?>
<?php js::set('changeProjectConfirmed', false); ?>

<div id="procreatesprint">
  <ul id="myTab" class="nav nav-tabs">
    <li id="sprintviewid">
    <?php echo html::a($this->createLink('sprint', 'index', "sprintID=$sprintID"),"User Story");?>
    </li>
    <li id="sprintviewid" class="active">
    <?php echo html::a($this->createLink('sprint', 'task', "sprintID=$sprintID"),"任务");?>
    </li>
    <li id="sprintviewid">
    <?php echo html::a($this->createLink('sprint', 'bug', "sprintID=$sprintID"),"Bug");?>
    </li>
    <li id="sprintviewid">
    <?php echo html::a($this->createLink('sprint', 'review', "sprintID=$sprintID"),"回顾会");?>
    </li>
    <li id="sprintviewid">
    <?php echo html::a($this->createLink('sprint', 'burn', "sprintID=$sprintID"),"燃尽图");?>
    </li>
	<li id="sprintviewid">
    <?php echo html::a($this->createLink('sprint', 'team', "sprintID=$sprintID"),"团队");?>
    </li>
  </ul>
</div>

<form class='form-condensed' method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['task']);?> <strong><?php echo $task->id;?></strong></span>
    <strong><?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name);?></strong>
    <small><?php echo html::icon($lang->icons['edit']) . ' ' . $lang->task->edit;?></small>
  </div>
</div>
<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <div class='form-group'>
        <?php echo html::input('name', $task->name, 'class="form-control" placeholder="' . $lang->task->name . '"');?>
      </div>
      <fieldset class='fieldset-pure'>
        <legend><?php echo $lang->task->desc;?></legend>
        <div class='form-group'>
          <?php echo html::textarea('desc', htmlspecialchars($task->desc), "rows='8' class='form-control'");?>
        </div>
      </fieldset>
      <fieldset class='fieldset-pure'>
        <legend><?php echo $lang->comment;?></legend>
        <div class='form-group'><?php echo html::textarea('comment', '',  "rows='5' class='form-control'");?></div>
      </fieldset>
      <fieldset class='fieldset-pure'>
        <legend><?php echo $lang->files;?></legend>
        <div class='form-group'><?php echo $this->fetch('file', 'buildform');?></div>
      </fieldset>
      <div class='actions actions-form' style='text-align:center'>
        <!-- <?php echo html::submitButton($lang->save) . html::linkButton($lang->goback, $this->inlink('view', "taskID=$task->id")) .html::hidden('consumed', $task->consumed);?> -->
        <?php echo html::submitButton($lang->save) . html::linkButton($lang->goback, $this->createLink('sprint', 'task', "projectID=$projectID")) .html::hidden('consumed', $task->consumed);?>
      </div>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <fieldset>
        <legend><?php echo $lang->task->legendBasic;?></legend>
        <table class='table table-form'> 
          <tr>
            <th class='w-80px'><?php echo $lang->task->project;?></th>
            <td><?php echo $projectStats[$projectID];?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->module;?></th>
            <td id="moduleIdBox"><?php echo html::select('module', $modules, $task->module, 'class="form-control " onchange="loadModuleRelated()"');?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->story;?></th>
            <td><span id="storyIdBox"><?php echo html::select('story', $stories, $task->story, "class='form-control'");?></span></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->assignedTo;?></th>
            <td><span id="assignedToIdBox"><?php echo html::select('assignedTo', $members, $task->assignedTo, "class='form-control '");?></span></td> 
          </tr>  
          <tr>
            <th><?php echo $lang->task->type;?></th>
            <td><?php echo html::select('type', $lang->task->typeList, $task->type, 'class=form-control onchange=check()');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->status;?></th>
          <td>
          <?php 
            if($task->type=='devel') {
              echo html::select('status', (array)$lang->task->statusList2, $task->status, 'class=form-control');
            } else {
              echo html::select('status', (array)$lang->task->statusList, $task->status, 'class=form-control');
            }
          ?>
          </td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->pri;?></th>
            <td><?php echo html::select('pri', $lang->task->priList, $task->pri, 'class=form-control');?> </td>
          </tr>
          <tr>
            <th><?php echo $lang->task->mailto;?></th>
            <td><?php echo html::select('mailto[]', $project->acl == 'private' ? $members : $users, str_replace(' ' , '', $task->mailto), 'class="form-control" multiple');?></td>
          </tr>
        </table>
	 <table class='table table-form'> 
          <tr>
            <th class='w-70px'><?php echo $lang->task->estStarted;?></th>
            <td><?php echo html::input('estStarted', $task->estStarted, "class='form-control form-date'");?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->realStarted;?></th>
            <td><?php echo html::input('realStarted', $task->realStarted, "class='form-control form-date'");?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->deadline;?></th>
            <td><?php echo html::input('deadline', $task->deadline, "class='form-control form-date'");?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->estimate;?></th>
            <td><?php echo html::input('estimate', $task->estimate, "class='form-control'");?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->consumed;?></th>
            <td><?php echo $task->consumed . ' '; common::printIcon('task', 'recordEstimate',   "taskID=$task->id", $task, 'list', '', '', 'iframe', true);?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->task->left;?></th>
            <td><?php echo html::input('left', $task->left, "class='form-control'");?></td>
          </tr>
        </table>
        </table>
	<table class='table table-form'> 
          <tr>
            <th class='w-70px'><?php echo $lang->task->openedBy;?></th>
            <td><?php echo $users[$task->openedBy];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->finishedBy;?></th>
            <td><?php echo html::select('finishedBy', $members, $task->finishedBy, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->finishedDate;?></th>
            <td><?php echo html::input('finishedDate', $task->finishedDate, 'class="form-control"');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->canceledBy;?></th>
            <td><?php echo html::select('canceledBy', $users, $task->canceledBy, 'class="form-control"');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->canceledDate;?></th>
            <td><?php echo html::input('canceledDate', $task->canceledDate, 'class="form-control"');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->closedBy;?></th>
            <td><?php echo html::select('closedBy', $users, $task->closedBy, 'class="form-control"');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->closedReason;?></th>
            <td><?php echo html::select('closedReason', $lang->task->reasonList, $task->closedReason, 'class="form-control"');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->task->closedDate;?></th>
            <td><?php echo html::input('closedDate', $task->closedDate, 'class="form-control"');?></td>
          </tr>
        </table>
      </fieldset>
    </div>
  </div>
</div>
</form>
<script language="javascript">
function check() {
   var r = document.getElementById("type").value;
   if (r == 'devel') {
      document.getElementById("status").length=0;
      for (i = 0;i < 9;i++) {
          document.getElementById("status").options[i] = new Option();
      }
      document.getElementById("status").options[0].value = 'wait';
      document.getElementById("status").options[0].innerText='未开始';
      document.getElementById("status").options[1].value='doing1';
      document.getElementById("status").options[1].innerText='开发中';
      document.getElementById("status").options[2].value='doing2';
      document.getElementById("status").options[2].innerText='review中';
      document.getElementById("status").options[3].value='doing3';
      document.getElementById("status").options[3].innerText='SIT阶段';
      document.getElementById("status").options[4].value='doing4';
      document.getElementById("status").options[4].innerText='UAT阶段';
      document.getElementById("status").options[5].value='done';
      document.getElementById("status").options[5].innerText='已完成';
      document.getElementById("status").options[6].value='pause';
      document.getElementById("status").options[6].innerText='已暂停';
      document.getElementById("status").options[7].value='cancel';
      document.getElementById("status").options[7].innerText='已取消';
      document.getElementById("status").options[8].value='closed';
      document.getElementById("status").options[8].innerText='已关闭';

   } else {
       document.getElementById("status").length=0;
       for(i=0;i<6;i++){
          document.getElementById("status").options[i]=new Option();
      }
      document.getElementById("status").options[0].value='wait';
      document.getElementById("status").options[0].innerText='未开始';
      document.getElementById("status").options[1].value='doing';
      document.getElementById("status").options[1].innerText='进行中';
      document.getElementById("status").options[2].value='done';
      document.getElementById("status").options[2].innerText='已完成';
      document.getElementById("status").options[3].value='pause';
      document.getElementById("status").options[3].innerText='已暂停';
      document.getElementById("status").options[4].value='cancel';
      document.getElementById("status").options[4].innerText='已取消';
      document.getElementById("status").options[5].value='closed';
      document.getElementById("status").options[5].innerText='已关闭';
   }
}
</script>
<?php include '../../common/view/footer.html.php';?>
