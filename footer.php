<footer>
    <a href="index.php" class="icon_home">avv</a>
    <a href="#" class="icon_calender">geg</a>
    <a href="login.php" class="icon_key">a</a>
    <?php
    if (login()) {
        echo "<a class='icon_plus' id='icon_plus'>a</a>";
    } ?>
</footer>
<script src="./function.js"></script>
</body>

</html>