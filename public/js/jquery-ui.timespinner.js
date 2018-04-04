$.widget( "ui.timespinner", $.ui.spinner, { 
    options: {     
      step: 1000,      
      page: 60
    },
    _parse: function( value ) {
      if ( typeof value === "string" ) {
        // already a timestamp
        if ( Number( value ) == value ) {
          return Number( value );
        }
        return +Globalize.parseDate( value,"HH':'mm':'ss" );
      }
      return value;
    },
    _format: function( value ) {
      return Globalize.format( new Date(value), "HH':'mm':'ss");
    }
  });

