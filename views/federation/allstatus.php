<div id="content" class="container-fluid dark-opac-bg">
  <div id="allstatus">
    <table class="table table-dark">
      <thead>
        <tr>
          <th scope="col">EID</th>
          <th scope="col">Team #</th>
          <th scope="col">Short Name</th>
          <th scope="col">Long Name</th>
          <th scope="col">Store Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $row) { ?>
        <?=$row?>
        <?php } ?>
      <tbody>
    </table>
  </div>
</div>
