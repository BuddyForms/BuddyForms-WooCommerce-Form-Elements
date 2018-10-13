/**
 * @file A WordPress-like hook system for JavaScript.
 *
 * This file demonstrates a simple hook system for JavaScript based on the hook
 * system in WordPress. The purpose of this is to make your code extensible and
 * allowing other developers to hook into your code with their own callbacks.
 *
 * There are other ways to do this, but this will feel right at home for
 * WordPress developers.
 *
 * @author Rheinard Korf
 * @license GPL2 (https://www.gnu.org/licenses/gpl-2.0.html)
 *
 * @requires underscore.js (http://underscorejs.org/)
 */

/**
 * Hooks object
 *
 * This object needs to be declared early so that it can be used in code.
 * Preferably at a global scope.
 */
var Hooks = Hooks || {}; // Extend Hooks if exists or create new Hooks object.

Hooks.actions = Hooks.actions || {}; // Registered actions
Hooks.filters = Hooks.filters || {}; // Registered filters

/**
 * Add a new Action callback to Hooks.actions
 *
 * @param tag The tag specified by do_action()
 * @param callback The callback function to call when do_action() is called
 * @param priority The order in which to call the callbacks. Default: 10 (like WordPress)
 */
Hooks.add_action = function( tag, callback, priority ) {

    if( typeof priority === "undefined" ) {
        priority = 10;
    }

    // If the tag doesn't exist, create it.
    Hooks.actions[ tag ] = Hooks.actions[ tag ] || [];
    Hooks.actions[ tag ].push( { priority: priority, callback: callback } );

}

/**
 * Add a new Filter callback to Hooks.filters
 *
 * @param tag The tag specified by apply_filters()
 * @param callback The callback function to call when apply_filters() is called
 * @param priority Priority of filter to apply. Default: 10 (like WordPress)
 */
Hooks.add_filter = function( tag, callback, priority ) {

    if( typeof priority === "undefined" ) {
        priority = 10;
    }

    // If the tag doesn't exist, create it.
    Hooks.filters[ tag ] = Hooks.filters[ tag ] || [];
    Hooks.filters[ tag ].push( { priority: priority, callback: callback } );

}

/**
 * Remove an Anction callback from Hooks.actions
 *
 * Must be the exact same callback signature.
 * Warning: Anonymous functions can not be removed.
 * @param tag The tag specified by do_action()
 * @param callback The callback function to remove
 */
Hooks.remove_action = function( tag, callback ) {

    Hooks.actions[ tag ] = Hooks.actions[ tag ] || [];

    Hooks.actions[ tag ].forEach( function( filter, i ) {
        if( filter.callback === callback ) {
            Hooks.actions[ tag ].splice(i, 1);
        }
    } );
}

/**
 * Remove a Filter callback from Hooks.filters
 *
 * Must be the exact same callback signature.
 * Warning: Anonymous functions can not be removed.
 * @param tag The tag specified by apply_filters()
 * @param callback The callback function to remove
 */
Hooks.remove_filter = function( tag, callback ) {

    Hooks.filters[ tag ] = Hooks.filters[ tag ] || [];

    Hooks.filters[ tag ].forEach( function( filter, i ) {
        if( filter.callback === callback ) {
            Hooks.filters[ tag ].splice(i, 1);
        }
    } );
}

/**
 * Calls actions that are stored in Hooks.actions for a specific tag or nothing
 * if there are no actions to call.
 *
 * @param tag A registered tag in Hook.actions
 * @options Optional JavaScript object to pass to the callbacks
 */
Hooks.do_action = function( tag, options ) {

    var actions = [];

    if( typeof Hooks.actions[ tag ] !== "undefined" && Hooks.actions[ tag ].length > 0 ) {

        Hooks.actions[ tag ].forEach( function( hook ) {

            actions[ hook.priority ] = actions[ hook.priority ] || [];
            actions[ hook.priority ].push( hook.callback );

        } );

        actions.forEach( function( hooks ) {

            hooks.forEach( function( callback ) {
                callback( options );
            } );

        } );
    }

}

/**
 * Calls filters that are stored in Hooks.filters for a specific tag or return
 * original value if no filters exist.
 *
 * @param tag A registered tag in Hook.filters
 * @options Optional JavaScript object to pass to the callbacks
 */
Hooks.apply_filters = function( tag, value, options ) {

    var filters = [];

    if( typeof Hooks.filters[ tag ] !== "undefined" && Hooks.filters[ tag ].length > 0 ) {

        Hooks.filters[ tag ].forEach( function( hook ) {

            filters[ hook.priority ] = filters[ hook.priority ] || [];
            filters[ hook.priority ].push( hook.callback );
        } );

        filters.forEach( function( hooks ) {

            hooks.forEach( function( callback ) {
                value = callback( value, options );
            } );

        } );
    }

    return value;
}

/***
 * EXAMPLES
 *
 * Note: Using the Hooks object assumes that it is available at the scope your
 * code is executing.
 *
 * Simplest way to test, if you have `node` installed run `node Hooks.js`
 */

// Filters ---------------------------------------------

// Note: Add filters before you apply filters. Its up to you to decide how to implement app wide.

// Anonymous example
/*Hooks.add_filter( 'my_filter', function( value, options ) {
    return value + ' [Option:' +  options.option1 + ']'
} ) // Default priority 10*/

// Non-anonymous example
/*function non_anon_filter( value, options ) {
    return 'Awesome: ' + value;
}*/
//Hooks.add_filter( 'my_filter', non_anon_filter, 1 ); // Priority 1

/*var my_value = 'Will be awesome'
var my_filtered_value = Hooks.apply_filters( 'my_filter', my_value, { option1: 'Optional option' } );
console.log( my_filtered_value );*/

// Remove filter
//Hooks.remove_filter( 'my_filter', non_anon_filter );

/*var my_value_2 = 'Will not be awesome';
var my_filtered_value_2 = Hooks.apply_filters( 'my_filter', my_value_2, { option1: 'Another option' } );
console.log( my_filtered_value_2 );*/

// Actions ---------------------------------------------

// Note: Add actions before you call do_action()

// Anonymous example
/*Hooks.add_action( 'my_action', function( options ) {

    console.log( 'Now you can perform custom actions at this exact moment of code execution.' );

} ) // Default priority 10*/

// Non-anonymous example
/*function non_anon_action( value, options ) {

    console.log( 'This line should execute before the previously defined action. Priority 1!' );

}
Hooks.add_action( 'my_action', non_anon_action, 1 ); // Priority 1

Hooks.do_action( 'my_action' ); // Not using options in this example*/