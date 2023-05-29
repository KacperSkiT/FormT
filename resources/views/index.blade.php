<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Formularz przewozu towarów</title>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="error">
        @if ($errors->any())
        <div class="error_text">
            {{__('validation.something_go_wrong')}}
        </div>
        <ul class="error_list">
            @foreach ($errors->all() as $error)
                <li>
                    {{ $error }}
                </li>
            @endforeach
        </ul>
        @endif
    </div>
<form class="cargo-form" method="POST" enctype="multipart/form-data">
    @csrf
<section class="transport-section">
    <h2>Transport</h2>
        <label for="from-input">Transport z:</label>
        <input type="text" class="from-input" name="from">
        <label for="to-input">Transport do:</label>
        <input type="text" class="to-input" name="to" >
        <div class="plane">
        <label for="plane-select">Typ samolotu:</label>
        <select class="plane-select" name="plane" >
            <option value="Wybierz">Wybierz...</option>
            <option value="Airbus A380">Airbus A380</option>
            <option value="Boeing 747">Boeing 747</option>
        </select>
        </div>
        <div class="date">
        <label for="date-input">Data transportu:</label>
        <input type="date" class="date-input" name="date">
        </div>
        <label for="documents-input">Dokumenty przewozowe:</label>
        <input type="file" class="documents-input" name="documents[]" multiple>
</section>
<section class="cargo-section">
    <h2>Ładunki</h2>
    <div class="cargos-container">
        <div class="cargo">
        <h3>Ładunek</h3>
        <label for="name-input-1">Nazwa ładunku:</label>
        <input type="text" class="name-input-1" name="name[]" >
        <label for="weight-input-1">Ciężar ładunku w kg:</label>
        <input type="number" class="weight-input-1" name="weight[]" >
        <label for="type-select-1">Typ ładunku:</label>
        <select class="type-select-1" name="type[]" >
            <option value="">Wybierz...</option>
            <option value="Ładunek normalny">Ładunek zwykły</option>
            <option value="Ładunek niebezpieczny">Ładunek niebezpieczny</option>
        </select>
        
        </div>
    </div>
    <button class="add-cargo-btn" type="button">Dodaj kolejny ładunek</button>
</section>
<div class="b_submit">
<button type="submit">Wyślij formularz</button>
</div>
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    //Add Input Fields
    $(document).ready(function() {
        var max_fields = 10; //Maximum allowed input fields 
        var cargo    = $(".cargos-container"); //Input fields wrapper
        var add_button = $(".add-cargo-btn"); //Add button class or ID
        var x = 1; //Initial input field is set to 1
        
        //When user click on add input button
        $(add_button).click(function(e){
            e.preventDefault();
            //Check maximum allowed input fields
            if(x < max_fields){ 
                x++; //input field increment
                 //add input field
                var cargoId = "cargo " + x;
                $(cargo).append('<div class="cargo"><h3>Ładunek</h3><label for="name-input-1">Nazwa ładunku:</label><input type="text" class="name-input-1" name="name[]" ><label for="weight-input-1">Ciężar ładunku w kg:</label><input type="number" class="weight-input-1" name="weight[]" ><label for="type-select-1">Typ ładunku:</label> <select class="type-select-1" name="type[]" ><option value="">Wybierz...</option><option value="Ładunek normalny">Ładunek zwykły</option><option value="Ładunek niebezpieczny">Ładunek niebezpieczny</option></select><button class="remove-cargo-btn" type="button">Usuń ładunek</button></div>');
            }
        });
        
        //when user click on remove button
        $(cargo).on("click",".remove-cargo-btn", function(e){ 
            e.preventDefault();
            $(this).parent('div').remove(); //remove inout field
            x--; //inout field decrement
        })
    });
    </script>
</body>
</html>