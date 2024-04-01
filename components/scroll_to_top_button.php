<style>
  #toTopArea {
    display: none;
    position: fixed;
    top: 0;
    right: 0;
    height: 100%;
    width: 150px;
    background-color: #EDEEF0;
    color: #000;
    text-align: center;
    line-height: 2;
    font-size: 14px;
    cursor: pointer;
    transition: opacity 0.3s ease;
    opacity: 0.2;
    z-index: 99;
    padding-top: 70px;
  }

  #toTopArea:hover {
    opacity: 1;
  }
</style>

<div id="toTopArea" onclick="scrollToTop()">
  <b style="color: #404040;">Наверх</b><img src="./assets/up.svg" style="height: 25px">
</div>

<script>
  window.onscroll = function() {
    scrollFunction();
  };

  function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      document.getElementById("toTopArea").style.display = "block";
    } else {
      document.getElementById("toTopArea").style.display = "none";
    }
  }

  function scrollToTop() {
    document.documentElement.scrollIntoView({behavior: 'smooth', block: 'start'});
  }
</script>