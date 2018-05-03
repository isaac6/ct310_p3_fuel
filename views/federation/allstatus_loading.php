<div id="content" class="container-fluid dark-opac-bg">
    <div id="allstatus-loading">
      <h2>Please wait while we load the federation statuses</h2>
      <p>
        <?=Asset::img('ajax-loader.gif', array('id' => 'loading-gif')); ?>
      </p>
      <?php
      Response::redirect('index.php/federation/allstatus', 'refresh');
      ?>
    </div>
</div>