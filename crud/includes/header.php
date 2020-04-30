<header>

    <div class="wrapper">

        <div class="header_con">

            <nav>

                <div class="user_info">
                    <a href="javascript:;"><?php echo $_SESSION['SESS_USER_ID'] ?></a>
                </div>

                <div class="nav_btns">
                    <a href="javascript:;"> Logout </a>
                </div>

                <form>
                    <input type="text" name="search" placeholder="Search">
                </form>
                
            </nav>

            <aside>
                <ul>
                    <li><a href="javascript:;"> Dashboard </a></li>
                    <li><a href="javascript:;"> Orders </a><li>
                    <li><a href="javascript:;"> Analytics </a></li>
                </ul>
            </aside>

        </div>

    </div>

</header>