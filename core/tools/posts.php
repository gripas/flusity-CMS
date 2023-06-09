<?php
define('ROOT_PATH', realpath(dirname(__FILE__) . '/../../') . '/');

require_once ROOT_PATH . 'core/template/header-admin.php';

?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/core/template/admin-menu-horizontal.php';?>
  <button class="btn btn-primary position-fixed start-0 translate-middle-y d-md-none tools-settings" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
      <i class="fas fa-bars"></i>
  </button>
 <?php require_once  $_SERVER['DOCUMENT_ROOT'] . '/core/tools/sidebar.php';?>
<div class="container-fluid mt-4 main-content admin-layout">
    <div class="row">
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4  content-up">

<?php   
    $possible_rows = [5, 15, 45, 55, 75, 150, 250, 350]; // galimos eilučių reikšmės
    $records_per_page = isset($_GET['rows']) ? intval($_GET['rows']) : 5; // pasirinktos eilutės

        $i = 1;
       
        
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($page - 1) * $records_per_page;
                $search_term = isset($_GET['search_term']) ? $_GET['search_term'] : '';
            
                $start = ($page - 1) * $records_per_page;

                $posts = getAllPostsPagination($db, $prefix, $start, $records_per_page, $search_term);
                $total_posts = countAllPosts($db, $prefix);
                $total_pages = ceil($total_posts / $records_per_page);
               // $allPost = getAllPosts($db, $prefix);
                $menuItems = getMenuItems($db, $prefix);
                $menuItemsIndexed = array_column($menuItems, null, 'id');
          ?>
       
            <div class="col-sm-9">
                <?php
                if (isset($_SESSION['success_message'])) {
                    echo "<div class='alert alert-success alert-dismissible fade show slow-fade'>
                        " . htmlspecialchars($_SESSION['success_message']) . "
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                    unset($_SESSION['success_message']);
                }

                if (isset($_SESSION['error_message'])) {
                    echo "<div class='alert alert-danger alert-dismissible fade show slow-fade'>
                        " . htmlspecialchars($_SESSION['error_message']) . "
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                    unset($_SESSION['error_message']);
                }
                ?>
            </div>
            <h2><?php echo t("Posts on pages");?></h2>
            <button type="button" class="btn btn-sm btn-primary" onclick="loadPostForm('create')" title="Add">
              <i class="fas fa-pencil"></i>
            </button>
            <div class="col-md-12 mt-3">
                 <!-- Išveda Puslapio redagavimo langą -->
            <div id="get-post-edit"></div>
               <div class="row">
                <div class="col-md-1">
                <form method="GET" id="rows-form">
                <label for="rows"><?php echo t("Per page:");?></label>
                <select name="rows" class="form-select" id="rows" onchange="document.getElementById('rows-form').submit()">
                    <?php foreach($possible_rows as $rows): ?>
                        <option value="<?php echo $rows; ?>" <?php echo ($rows == $records_per_page ? 'selected' : ''); ?>>
                            <?php echo $rows; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
                </div>
                <div class="col-md-9">
                <label for="search_term"><?php echo t("Search for posts or pages");?></label>
                <div class="input-wrapper">
                     <input type="text" id="search_term" class="form-control search-input-long" name="search_term" placeholder="<?php echo t("Search post...");?>">
                    <span id="clear-search" class="clear-button">&times;</span>
                 </div>

                </div>
            
          </div>
        </div>
         
           
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 3%;"><?php echo t("No.");?></th>
                        <th style="width: 17%;"><?php echo t("Title");?></th>
                        <th style="width: 40%;"><?php echo t("Content");?></th>
                        <th style="width: 12%;"><?php echo t("Page");?></th>
                        <th style="width: 3%;"><i title="<?php echo t("Public");?>" class="fas fa-eye"></i></th>
                        <th style="width: 8%;"><?php echo t("Author");?></th>
                        <th style="width: 10%;"><?php echo t("Actions");?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($posts as $post) { 
                        if (isset($menuItemsIndexed[$post['menu_id']])) {
                            $post['menu_name'] = $menuItemsIndexed[$post['menu_id']]['name'];
                        } else {
                            $post['menu_name'] = '';
                        }
                     ?>
                      <tr class="posts-row" data-key="<?php echo htmlspecialchars($post['title']); ?>" data-value="<?php echo htmlspecialchars($post['content']); ?>" data-menu_name="<?php echo htmlspecialchars($post['menu_name']); ?>">
                           <td><?php echo $i++; ?>.</td>
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            
                            <td>
                            <?php
                                $content = htmlspecialchars_decode($post['content']);
                                $content = preg_replace('/<img[^>]+>/i', '[image or file]', $content);
                                $content = preg_replace('/<hr[^>]*>/i', '___', $content);

                                // Pakeičiamas visų naujų eilučių simbolis į tarpą
                                $content = str_replace(array("\r", "\n"), ' ', $content);
                                // Pašalinamos visos HTML žymos
                                $content = strip_tags($content);

                                $max_length = 150;
                                if (strlen($content) > $max_length) {
                                    echo substr($content, 0, $max_length) . '...';
                                } else {
                                    echo $content;
                                }
                            ?>
                            </td>
                            <td><?php echo htmlspecialchars($post['menu_name']); ?></td>
                            <td><?php 
                                      if ($post['status'] == 'published') {
                                          echo 'On';
                                      } elseif ($post['status'] == 'draft') {
                                          echo 'Off';
                                      }
                                  ?></td>
                            <td><?php echo htmlspecialchars($user_name); ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" onclick="loadPostEditForm(<?php echo $post['id']; ?>)" title="<?php echo t("Edit");?>">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deletePostModal" data-post-id="<?php echo $post['id']; ?>" title="<?php echo t("Delete");?>">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3">
            <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                    <span aria-hidden="true">«</span>
                     </a>
                 </li>
          <?php  $num_pages_to_display = 5;
                 for ($i = max(1, $page - $num_pages_to_display); $i <= min($page + $num_pages_to_display, $total_pages); $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
            <?php endfor; ?>
                    <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">»</span>
                        </a>
                    </li>
            </ul>
        </nav>
            </div>
      
      </main>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel"><?php echo t("Confirm deletion");?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <?php echo t("Are you sure you want to delete this post?");?> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo t("Cancel");?></button>
        <button type="button" class="btn btn-danger delete-post-btn" id="confirm-delete-btn"><?php echo t("Delete");?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-body" id="previewModalBody">
      </div>
    </div>
  </div>
</div>
<div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="imageSelectOffcanvas" data-bs-backdrop="false" data-bs-scroll="true">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Select an Image</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">

  </div>
</div>

<script>
  function loadPostEditForm(postId) {
    loadPostForm('edit', postId);
  }
  
  $('button[data-bs-target="#deletePostModal"]').on('click', function () {
    const postId = $(this).data('post-id');
    $('#confirmDeleteModal').data('post-id', postId);
    $('#confirmDeleteModal').modal('show');
  });
  
  $('#confirm-delete-btn').on('click', function () {
    const postId = $('#confirmDeleteModal').data('post-id');
    $.ajax({
      type: 'POST',
      url: 'delete_post.php',
      data: {
        action: 'delete_post',
        post_id: postId
      },
      success: function(response) {
        $('#confirmDeleteModal').modal('hide');
        window.location.href = 'posts.php';
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown);
      }
    });
  });
  
  $(document).ready(function() {
    var $offCanvas = $('#imageSelectOffcanvas');
    $(document).click(function(event) {
        if (!$(event.target).closest('.offcanvas').length && $offCanvas.hasClass('show')) {
            $offCanvas.offcanvas('hide');
        }
    });
    $offCanvas.on('mouseleave', function(event) {
        $offCanvas.offcanvas('hide');
    });
});
  
 document.querySelector('#search_term').addEventListener('input', function() {
        if (this.value !== '') {
            document.querySelector('#clear-search').style.display = 'block';
        } else {
            document.querySelector('#clear-search').style.display = 'none';
        }
 });

 document.querySelector('#clear-search').addEventListener('click', function() {
    var searchInput = document.querySelector('#search_term');
    searchInput.value = '';
    this.style.display = 'none';
    var event = new Event('keyup');
    searchInput.dispatchEvent(event);
 });
 
 $(document).ready(function() {
    $('#search_term').on('keyup', function() {
        var search_term = $(this).val().toLowerCase();

        $('.posts-row').each(function() {
            var key = $(this).data('key').toLowerCase();
            var value = $(this).data('value').toLowerCase();
            var menu_name = $(this).data('menu_name').toLowerCase();

            if (key.includes(search_term) || value.includes(search_term) || menu_name.includes(search_term)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});

</script>
<script src="js/admin-post-edit.js"></script>
<?php require_once ROOT_PATH . 'core/template/admin-footer.php';?>