function toggleSidebar() {
    let sidebar = document.querySelector('.sidebar');
    let content = document.querySelector('.main-content');
    sidebar.style.width = sidebar.style.width === "70px" ? "250px" : "70px";
    content.style.marginLeft = sidebar.style.width === "70px" ? "70px" : "250px";
}

function toggleSubmenu(id) {
    let submenu = document.getElementById(id);
    submenu.style.display = submenu.style.display === "block" ? "none" : "block";
}

function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
}

function loadPage(page) {
    $("#content-area").html("<h3>Loading...</h3>");
    $.ajax({
        url: "pages/" + page + ".php",
        success: function (data) {
            $("#content-area").html(data);
        }
    });
}
