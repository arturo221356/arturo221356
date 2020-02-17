<!-- barra de  navegacion sobre tabla -->

<nav class="navbar navbar-expand-lg navbar-light " style="background-color: #DEDEDE;">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <a class="navbar-brand" href="#">@yield('TableNavbarName')</a>
      
      <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
        @yield('TableNavbarButtons')
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2 search" type="text" placeholder="Search" aria-label="Search" onsearch="tablesearch()" id="tableSearchField">
        
      </form>
    </div>
  </nav>

 <script>



    //buscar en la tabla !!    
  function tablesearch() {
  const input = document.getElementById("tableSearchField");
  const inputStr = input.value.toUpperCase();
  document.querySelectorAll('#myTable tr:not(.tablehead)').forEach((tr) => {
    const anyMatch = [...tr.children]
      .some(td => td.textContent.toUpperCase().includes(inputStr));
    if (anyMatch) tr.style.removeProperty('display');
    else tr.style.display = 'none';
  });
}

        
        
        </script>