(function($){
    console.log( "countPos = " + countPos );
    console.log( "countEdu = " + countEdu );

    main();

    function main() {
        let iMax;

        iMax = countPos + 1;
        countPos = 0;
        for( let i=1; i<iMax; i++ ){
            console.log( "Triggering #addPos click event..." );
            addPosClick();
        }

        iMax = countEdu + 1;
        countEdu = 0;
        for( let i=1; i<iMax; i++ ){
            console.log( "Triggering #addEdu click event..." );
            addEduClick();
        }
    }

    function treatData( elem ){
        if( typeof elem !== 'undefined' )
            return elem;
        else
            return '';
    }

    function validate2() {
        console.log( "Checking for empty fields..." );
    
        const first_name = $.trim( $('#first_name').val() );
        const last_name = $.trim( $('#last_name').val() );
        const email = $.trim( $('#email').val() );
        const headline = $.trim( $('#headline').val() );
        const summary = $('#summary').val();
        const optionalValues = [];
    
        for( let i=1; i<=countPos; i++ ){
            field = $('#position'+i).find(`input[name="year${i}"]`);
            if( field.length )
                optionalValues.push( $.trim( field.val() ) );

            field = $('#position'+i).find(`textarea[name="desc${i}"]`);
            if( field.length )
                optionalValues.push( $.trim( field.val() ) );
        }

        for( let i=1; i<=countEdu; i++ ){
            field = $('#education'+i).find(`input[name="edu_year${i}"]`);
            if( field.length )
                optionalValues.push( $.trim( field.val() ) );

            field = $('#education'+i).find(`input[name="edu_school${i}"]`);
            if( field.length )
                optionalValues.push( $.trim( field.val() ) );
        }

        try{
            if( ! first_name ) return false;
            if( ! last_name ) return false;
            if( ! email ) return false;
            if( ! headline ) return false;
            if( ! summary ) return false;
            for( value of optionalValues )
                if( ! value ) return false;
            return true;
        } catch(e) {
            console.log( "ERROR" );
            return false;
        }
        return false;
    }

    function validate() {
        console.log( "Checking for empty fields..." );

        const fields = $('.field');
        const values = [];

        for( field of fields ){
            values.push( field.value );
        }

        try {
            for( value of values ){
                if( ! value ) return false;
            }
            return true;
        } catch(e) {
            console.log( "ERROR" );
            return false;
        }
        return false;
    }

    // Provisional solution. Check how to use JQuery trigger function.
    function addPosClick() {
        if( countPos >= 9 ) {
            alert( "Maximum of nine position entries exceeded" );
            return;
        }
        countPos++;
        window.console && console.log( "Adding position "+countPos );

        year = treatData( data.year[countPos-1] );
        desc = treatData( data.desc[countPos-1] );

        $('#position_fields').append(
            `<div id="position${countPos}" class="optional_fields"> \
                <p>Year: \
                    <input class="field" type="text" name="year${countPos}" value="${year}"> \
                    <input type="button" value="-" \
                        onclick="$('#position${countPos}').remove();return false;"> \
                </p> \
                <textarea class="field" name="desc${countPos}" rows="8" cols="80">${desc}</textarea> \
            </div>`
        );
    }

    // Provisional solution. Check how to use JQuery trigger function.
    function addEduClick() {
        if( countEdu >= 9 ){
            alert( "Maximum of nine education entries exceeded" );
            return;
        }
        countEdu++;
        window.console && console.log( "Adding education "+countEdu );

        edu_year = treatData( data.edu_year[countEdu-1] );
        edu_school = treatData( data.edu_school[countEdu-1] );

        $('#education_fields').append(
            `<div id="education${countEdu}" class="optional_fields"> \
                <p>Year: \
                    <input class="field" type="text" name="edu_year${countEdu}" \
                        value='${edu_year}'> \
                    <input type="button" value="-" \
                        onclick="$('#education${countEdu}').remove();return false;"> \
                </p> \
                <p>School: \
                    <input class="school field" type="text" size="60" name="edu_school${countEdu}" 
                        value='${edu_school}'> \
                </p> \
            </div>`
        );

        $('.school').autocomplete({ source: "school.php" });
    }

    $(document).ready(function(){
        window.console && console.log('Document already called');

        $('#add').click( function(event) {
            if( validate() === false ){
                alert( "All fields are required" );
                event.preventDefault();
            }
        });

        $('#addEdu').click( function(event) {
            event.preventDefault();
            addEduClick();
        });

        $('#addPos').click( function(event) {
            event.preventDefault();
            addPosClick();
        });
    });
})(jQuery);