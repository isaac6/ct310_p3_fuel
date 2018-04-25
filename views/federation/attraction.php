<div id="content" class="container-fluid dark-opac-bg">
  <div id="attraction">
    <center><h2><?=$attraction['name']?></h2></center>
    <center><?=Asset::img($attraction['img']);?></center>
    <center><h5><?=$attraction['details']?></h5></center>
  </div>
  <div id="comments">
    <h3>Comments:</h3>
    <?php foreach ($comments as $comment):?>
      <ul>
        <?php if ($comment['attractionID'] === $attraction['attractionID']): ?>
          <?php if (Auth::check() && Auth::get('group') === '10'): ?>
            <li class="comment">
              <?php echo "<strong>" . $comment['username'] . ":</strong> " . $comment['content']; ?>
              <br/>
              <div class="commentDate">
                <?php echo $comment['time']; ?>
              </div>
              <div id="commentEdit">
                <form method="post">
                  (Admin) Edit comment: <textarea name='content' id="commentEditTextArea"><?=$comment['content'];?></textarea>
                  <div id="buttonGroup">
                  <button type="submit" class="btn btn-primary btn-xs" name="save_id" value="<?=$comment['commentID']?>">Save</button>
                </form>
                <form method="post" action="<?=Uri::create('index.php/travelco/delete_comment');?>">
                  <button type="submit" class="btn btn-danger btn-xs" name="delete_id" value="<?=$comment['commentID']?>">Delete</button>
                  </div>
                </form>
              </div>
            </li>
          <?php else: ?>
            <li class="comment">
              <?php echo "<strong>" . $comment['username'] . ":</strong> " . $comment['content']; ?>
              <br/>
              <div class="commentDate">
                <?php echo $comment['time']; ?>
              </div>
            </li>
          <?php endif;?>
        <?php endif;?>
      </ul>
    <?php endforeach; ?>
    <!--add new comment box-->
    <div>
      <?php if (Auth::check()): ?>
        <form method="post" id="commentNew" action="<?=Uri::create('index.php/travelco/add_comment');?>" >
          Add comment: <textarea name='new_content' id="commentEditTextArea"></textarea>
          <button type="submit" class="btn btn-success btn-xs" name="add_id" value="<?=$attraction['id'];?>">Add</button>
        </form>
      <?php endif;?>
    </div>
  </div>
</div>
