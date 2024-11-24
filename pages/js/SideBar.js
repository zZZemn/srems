$(document).ready(function () {
  var IsNavDropdownMenu = false;

  var isSideBarOpen = true;
  var btnToggleSideBar = $("#btn-toggle-side-bar");
  var sidebar = $("#side-bar");
  var mainContainer = $("#main-container");

  const toggleSideBar = (action) => {
    if (action == 1) {
      $(sidebar).css("transform", "translateX(0)");
      $(mainContainer).css({
        left: "250px",
        width: "calc(100% - 250px)",
      });
      isSideBarOpen = true;
    } else {
      $(sidebar).css("transform", "translateX(-100%)");
      $(mainContainer).css({
        left: "0",
        width: "100%",
      });
      isSideBarOpen = false;
    }
  };

  const checkWindowSize = () => {
    var windowWidth = $(window).width();
    if (windowWidth < 800) {
      toggleSideBar(0);
    } else {
      toggleSideBar(1);
    }
  };

  $(btnToggleSideBar).click(function (e) {
    e.preventDefault();
    if (isSideBarOpen) {
      toggleSideBar(0);
    } else {
      toggleSideBar(1);
    }
  });

  $(window).resize(function () {
    checkWindowSize();
  });

  $("#navDropdownMenuButton").click(function (e) {
    e.preventDefault();

    if (IsNavDropdownMenu) {
      $("#navDropdownMenu").css("display", "none");
    } else {
      $("#navDropdownMenu").css("display", "block");
    }

    IsNavDropdownMenu = !IsNavDropdownMenu;
  });

  checkWindowSize();
});
