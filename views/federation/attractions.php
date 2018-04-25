<div id="content" class="container-fluid dark-opac-bg">
  <center><h2>Attractions:</h2></center>
  <div id="attractionsList">
    <?php if (empty($attractions)) {
      echo "<h4>None</h4>";
    } else {
      foreach ($attractions as $attraction):?>
        <form method = "post">
          <a href="<?=Uri::create('index.php/travelco/attraction/' . $attraction['attractionID']); ?>"><?=$attraction['name']; ?></a>
            <?php if (Auth::check() && Auth::get('group') === '10'): ?>
              <button type="submit" class="btn btn-danger btn-xs" name="delete_id" value="<?=$attraction['id']?>">Delete</button>
            <?php endif;?>
        </form>
      <?php endforeach; } ?>
  </div>
</div>
