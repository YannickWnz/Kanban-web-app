<?php 

include './config/dbh.php';

if(!isset($_SESSION['userID']) && !isset($_SESSION['username'])) {
  header('location: login.php');
}

$userID = $_SESSION['userID'];
$username = $_SESSION['username'];



?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="icon" type="image/png" sizes="32x32" href="./starter-code/assets/favicon-32x32.png">

  <!-- FontAwesome v6.6.6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- css link -->
  <link rel="stylesheet" href="./css/style.css">
  
  <title>Kanban task management web app</title>
</head>
<body>

  <div class="app-body-content">
    <div class="sidebar">
      <div class="side-logo-container">
        <img src="./starter-code/assets/logo-light.svg" alt="">
      </div>
      <div class="boards-section">
        <h1>ALL BOARDS <span>(0)</span></h1>
        <div class="created-boards">
            <!-- <div class="board-item active-board">
              <i class="fa-solid fa-window-restore"></i>
              <span class="board-item-name">Platform Launch</span>
            </div> -->
        </div>
        <div class="createBoardBtn">
          <i class="fa-solid fa-window-restore"></i>
          <i class="fa-solid fa-plus"></i>
          <span>Create New Board</span>
        </div>
      </div>
      <div class="sidebar-bottom-contents">
        <div class="mode-switch">
          <i class="fa-solid fa-right-from-bracket"></i>
          <a href="logout.php">Logout</a>
        </div>
        <p class="hideSidebar">
          <i class="fa-solid fa-eye-slash"></i>
          Hide Sidebar
        </p>
      </div>
    </div>
    <div class="body-container">
      <div class="wrapper">
        <div class="board">
          <nav class="board-nav">
            <div class="logo-container toggleLogoDisplay">
              <img src="./starter-code/assets/logo-light.svg" alt="">
            </div>
            <div class="nav-contents">
              <h1></h1>
              <div class="newTaskBtnWrapper">
                <button disabled><i class="fa-solid fa-plus"></i> Add New Task</button>
                <i class="fa-solid fa-ellipsis-vertical"></i>
                <div class="amend-board-box display-none">
                  <a>Edit Board</a>
                  <a>Delete Board</a>
                </div>
              </div>
            </div>
          </nav>
          <div class="showSidebarBtn">
            <i class="fa-solid fa-eye"></i>
          </div>
          <!-- empty board content START -->
          <div class="empty-board-content"></div>
          <!-- empty board content END -->

          <!-- add new board modal pop up START -->
          <div class="addNewBoard"></div>
          <form id="addBoardForm" method="POST" onsubmit="return handleNewBoard()">
            <p>Add New Board</p>
            <div class="newBoardName">
              <label for="boardName">Board Name</label><br>
              <input type="text" id="board-name" name="boardName" placeholder="e.g. Web Design">
            </div>
            <div class="createBoardBtn">
              <input type="submit" name="submitBoardName" value="Create New Board">
            </div>
            <p class="boardErrorMsg">Board Name can't be empty</p>
          </form>
          <!-- add new board modal pop up START -->
          
          <!-- filled board with columns task START -->
          <div class="filled-board">
            <div class="filled-board-wrapper">
                <div class="todo">
                    <div class="column-name">
                        <span class="column-color"></span>
                        <span>TODO</span>
                        <span class="task-number">(0)</span>
                    </div>
                    <div class="column-task-wrapper">
                        <!-- <div class="column-task">
                          <p>Create paper prototypes and conduct 10 usability tests with potential customers</p>
                          <p>1 of 1 substasks</p>
                        </div>
                        <div class="column-task">
                          <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa obcaecati rerum ex aliquid cum repellat voluptatum quisquam. Voluptatum consequatur ad, ipsum dolor at doloribus aperiam, possimus nemo facilis, obcaecati sequi dignissimos blanditiis est beatae maxime officia. Temporibus eaque labore consequuntur!</p>
                          <p>1 of 1 substasks</p>
                        </div> -->
                    </div>
                </div>
                <div class="doing">
                    <div class="column-name">
                        <span class="column-color"></span>
                        <span class="column">DOING</span>
                        <span class="task-number">(0)</span>
                    </div>
                    <div class="column-task-wrapper"></div>
                </div>
                <div class="done">
                    <div class="column-name">
                        <span class="column-color"></span>
                        <span>DONE</span>
                        <span class="task-number">(0)</span>
                    </div>
                    <div class="column-task-wrapper"></div>
                </div>
                <div class="on-hold">
                    <div class="column-name">
                        <span class="column-color"></span>
                        <span>ON HOLD</span>
                        <span class="task-number">(0)</span>
                    </div>
                    <div class="column-task-wrapper"></div>
                </div>
            </div>
          </div>
          <!-- filled board with columns task END -->

          <!-- add new task START -->
          <div class="add-task-bg-overlay"></div>
          <form class="add-task-form" onsubmit="return newTaskValue()">
            <p>Add New Task</p>
            <div class="new-task-name">
              <label for="task-name">Title</label><br>
              <input type="text" placeholder="e.g. Take coffee break">
              <span class="newTaskEmptyError">Can't be empty</span>
            </div>
            <div class="new-task-description">
              <label for="task-description">Description</label><br>
              <!-- <input type="text" placeholder="e.g. It's always good to take a break. This 15 minutes break will recharge thee batteries a little."> -->
              <!-- <textarea name="task-description"></textarea> -->
              <textarea name="task-description" placeholder="e.g. It's always good to take a break. This 15 minutes break will recharge thee batteries a little." id="" cols="30" rows="5"></textarea>
            </div>
            <div class="new-task-substasks">
              <label for="task-substasks">Substasks</label><br>
              <div class="substask-input-wrapper">
                <input type="text" autocomplete="off" name="array[]" placeholder="e.g. Make coffee">
                <i class="fa-solid fa-xmark"></i>
                <span class="newTaskEmptyError">Can't be empty</span>
              </div>
            </div>
            <div class="add-substasks-btn" onclick="return addNewSubstask(deleteSubstask)">
            <!-- <div class="add-substasks-btn" onclick=""> -->
              <i class="fa-solid fa-plus"></i>
              <span>Add New Substask</span>
            </div>
            <div class="task-status">
              <label for="task-status">Status</label>
              <div class="status-options">
                <select id="task-status">
                  <option value="todo">Todo</option>
                  <option value="doing">Doing</option>
                  <option value="done">Done</option>
                  <option value="on-hold">On-Hold</option>
                </select>
              </div>
            </div>
            <div class="create-task-btn">
              <input type="submit" value="Create Task" onsubmit="">
              <!-- <button>Create task</button> -->
            </div>
          </form>
          <!-- add new task END -->

          <!-- view task START -->
          <div class="view-task-bg-overlay display-none"></div>
          <div class="view-task-modal display-none">
            <!-- <div class="view-task-title">
              <h3>Research pricing points of various competitors and trial of different business models</h3>
              <i class="fa-solid fa-ellipsis-vertical"></i>
              <div class="amend-view-task display-none">
                <a>Edit task</a>
                <a>Delete task</a>
              </div>
            </div>
            <div class="view-task-description">
              <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consectetur corrupti voluptatibus praesentium nihil, pariatur nesciunt iure error voluptatum. Unde quod ullam omnis exercitationem enim placeat? Eaque consequatur dolorem distinctio non?</p>
            </div>
            <div class="view-task-subtasks">
              <h5>Subtasks (0 of 3)</h5>
                <div class="subtask-checkbox-wrapper">
                  <input type="checkbox" name="" id="">
                  <p>Research competitor pricing busines models</p>
                </div>
                <div class="subtask-checkbox-wrapper">
                  <input type="checkbox" name="" id="">
                  <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Pariatur odio sint incidunt eaque cumque sequi.</p>
                </div>
                <div class="subtask-checkbox-wrapper">
                  <input type="checkbox" name="" id="">
                  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Optio modi fugiat atque quisquam unde molestiae. Ipsam id omnis eaque possimus.</p>
                </div>
            </div> -->
          </div>
          <!-- view task END -->

          <!-- edit baord prompt START -->
          <div class="edit-board-bg-overlay display-none"></div>
          <div class="edit-board-form display-none">
            <form id="addBoardForm" class="edit-board" method="POST">
              <p>Add New Board</p>
              <div class="newBoardName">
                <label for="boardName">Board Name</label><br>
                <input type="text" id="board-name" name="boardName" placeholder="e.g. Web Design">
              </div>
              <div class="createBoardBtn">
                <input type="submit" name="submitBoardName" value="Save changes">
              </div>
              <p class="boardErrorMsg">Board Name can't be empty</p>
            </form>
          </div>
          <!-- edit baord prompt END -->

          <!-- delete board prompt START -->
          <div class="delete-board-bg-overlay display-none"></div>
          <div class="delete-board-prompt display-none">
            <h3>Delete board?</h3>
            <p>Are you sure you want to delete 'Platform Launch' board? This action will remove all columns and tasks and cannot be reserved.</p>
            <div class="delete-btn-options">
              <button class="delete">Delete</button>
              <button class="cancel">Cancel</button>
            </div>
          </div>
          <!-- delete board prompt END -->

          <!-- edit task prompt START -->
          <div class="edit-task-bg-overlay edit-task-bg display-none"></div>
          <div class="edit-task-form display-none">
            <form class="edit-task-form" onsubmit="">
              <p>Edit Task</p>
              <div class="edit-task-name">
                <label for="task-name">Title</label><br>
                <input type="text" placeholder="e.g. Take coffee break">
                <span class="newTaskEmptyError">Can't be empty</span>
              </div>
              <div class="edit-task-description">
                <label for="task-description">Description</label><br>
                <textarea name="task-description" placeholder="e.g. It's always good to take a break. This 15 minutes break will recharge thee batteries a little." id="" cols="30" rows="5"></textarea>
              </div>
              <div class="edit-task-substasks">
                <label for="task-substasks">Substasks</label><br>
                  <div class="edit-substask-input-wrapper">
                    <input type="text" autocomplete="off" name="array[]" placeholder="e.g. Make coffee">
                    <i class="fa-solid fa-xmark"></i>
                    <span class="editTaskEmptyError">Can't be empty</span>
                  </div>
              </div>
              <div class="edit-substasks-btn" onclick="">
                <i class="fa-solid fa-plus"></i>
                <span>Add New Substask</span>
              </div>
              <div class="edit-task-status">
                <label for="task-status">Status</label>
                <div class="status-options options">
                  <select id="edited_task_status">
                    <option value="todo" selected>Todo</option>
                    <option value="doing">Doing</option>
                    <option value="done">Done</option>
                    <option value="on-hold">On-Hold</option>
                  </select>
                </div>
              </div>
              <div class="create-task-btn">
                <input type="submit" value="Save changes" onsubmit="">
              </div>
            </form>
          </div>
          <!-- edit task prompt END -->

          <!-- delete task prompt START -->
          <div class="delete-task-bg-overlay display-none"></div>
          <div class="delete-task-prompt display-none">
            <h3>Delete this task?</h3>
            <p>Are you sure you want to delete 'Platform Launch' board? This action will remove all columns and tasks and cannot be reserved.</p>
            <div class="delete-task-btn-options">
              <button class="delete">Delete</button>
              <button class="cancel">Cancel</button>
            </div>
          </div>
          <!-- delete task prompt END -->

          <!-- confirmation message START -->
          <div class="confirmation-message">
            <!-- <div class="confirmation-container"> -->
              <p>Your task has been updated</p>
            <!-- </div> -->
          </div>
          <!-- confirmation message END -->

        </div>
      </div>
    </div>
  </div>



  <!-- <script src="./js-scripts/scripts.js"></script> -->
  <!-- <script src="./js-scripts/main-test.js"></script> -->
  <script src="./js-scripts/main.js"></script>

</body>
</html>