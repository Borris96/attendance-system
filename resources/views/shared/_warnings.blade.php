  @if(session()->has("danger"))
      <p style="color: red;">
        {{ session()->get("danger") }}
      </p>
  @elseif(session()->has('success'))
      <p style="color: green;">
        {{ session()->get("success") }}
      </p>
  @endif
