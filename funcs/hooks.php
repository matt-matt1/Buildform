<?php
namespace Yuma;

use Yuma\HTML;
/**
 * Adds a callback function to a filter hook.
 *
 * The following example shows how a callback function is bound to a filter hook.
 *
 * Note that `$example` is passed to the callback, (maybe) modified, then returned:
 *
 *     function example_callback( $example ) {
 *         // Maybe modify $example in some way.
 *         return $example;
 *     }
 *     add_filter( 'example_filter', 'example_callback' );
 *
 * Bound callbacks can accept from none to the total number of arguments passed as parameters
 * in the corresponding apply_filters() call.
 *
 * In other words, if an apply_filters() call passes four total arguments, callbacks bound to
 * it can accept none (the same as 1) of the arguments or up to four. The important part is that
 * the `$accepted_args` value must reflect the number of arguments the bound callback *actually*
 * opted to accept. If no arguments were accepted by the callback that is considered to be the
 * same as accepting 1 argument. For example:
 *
 *     // Filter call.
 *     $value = apply_filters( 'hook', $value, $arg2, $arg3 );
 *
 *     // Accepting zero/one arguments.
 *     function example_callback() {
 *         ...
 *         return 'some value';
 *     }
 *     add_filter( 'hook', 'example_callback' ); // Where $priority is default 10, $accepted_args is default 1.
 *
 *     // Accepting two arguments (three possible).
 *     function example_callback( $value, $arg2 ) {
 *         ...
 *         return $maybe_modified_value;
 *     }
 *     add_filter( 'hook', 'example_callback', 10, 2 ); // Where $priority is 10, $accepted_args is 2.
 *
 * *Note:* The function will return true whether or not the callback is valid.
 * It is up to you to take care. This is done for optimization purposes, so
 * everything is as quick as possible.
 *
 * @since 0.71
 *
 * @global Hook[] $hooks A multidimensional array of all hooks and the callbacks hooked to them.
 *
 * @param string   $hook_name     The name of the filter to add the callback to.
 * @param callable $callback      The callback to be run when the filter is applied.
 * @param int      $priority      Optional. Used to specify the order in which the functions
 *                                associated with a particular filter are executed.
 *                                Lower numbers correspond with earlier execution,
 *                                and functions with the same priority are executed
 *                                in the order in which they were added to the filter. Default 10.
 * @param int      $accepted_args Optional. The number of arguments the function accepts. Default 1.
 * @return true Always returns true.
 */
function add_hook( $hook_name, $callback, $priority = 10, $accepted_args = 1 ) {
	global $hooks;

	if ( !isset( $hooks[$hook_name] )) {
		$hooks[$hook_name] = new HTML\Hook();
	}

	$hooks[$hook_name]->add( $hook_name, $callback, $priority, $accepted_args );

	return true;
}

//<?php
/**
 * The plugin API is located in this file, which allows for creating actions
 * and filters and hooking functions, and methods. The functions or methods will
 * then be run when the action or filter is called.
 *
 * The API callback examples reference functions, but can be methods of classes.
 * To hook methods, you'll need to pass an array one of two ways.
 *
 * Any of the syntaxes explained in the PHP documentation for the
 * {@link https://www.php.net/manual/en/language.pseudo-types.php#language.types.callback 'callback'}
 * type are valid.
 *
 * Also see the {@link https://developer.wordpress.org/plugins/ Plugin API} for
 * more information and examples on how to use a lot of these functions.
 *
 * This file should have no external dependencies.
 *
 * @package WordPress
 * @subpackage Plugin
 * @since 1.5.0
 */

// Initialize the filter globals.
require __DIR__ . '/../class/Yuma/HTML/Hook.php';

/** @var Hooks[] $hooks */
global $hooks;

/** @var int[] $actions */
global $actions;

/** @var int[] $nhooks */
global $nhooks;

/** @var string[] $current_filter */
global $current_filter;

if ($hooks) {
	$hooks = Hooks::build_preinitialized_hooks( $hooks );
} else {
	$hooks = array();
}

if (!isset( $actions )) {
	$actions = array();
}

if (!isset( $nhooks )) {
	$nhooks = array();
}

if (!isset( $current_filter )) {
	$current_filter = array();
}

/**
 * Calls the callback functions that have been added to a filter hook.
 *
 * This function invokes all functions attached to filter hook `$hook_name`.
 * It is possible to create new filter hooks by simply calling this function,
 * specifying the name of the new hook using the `$hook_name` parameter.
 *
 * The function also allows for multiple additional arguments to be passed to hooks.
 *
 * Example usage:
 *
 *     // The filter callback function.
 *     function example_callback( $string, $arg1, $arg2 ) {
 *         // (maybe) modify $string.
 *         return $string;
 *     }
 *     add_filter( 'example_filter', 'example_callback', 10, 3 );
 *
 *     /*
 *      * Apply the filters by calling the 'example_callback()' function
 *      * that's hooked onto `example_filter` above.
 *      *
 *      * - 'example_filter' is the filter hook.
 *      * - 'filter me' is the value being filtered.
 *      * - $arg1 and $arg2 are the additional arguments passed to the callback.
 *     $value = apply_filters( 'example_filter', 'filter me', $arg1, $arg2 );
 *
 * @since 0.71
 * @since 6.0.0 Formalized the existing and already documented `...$args` parameter
 *              by adding it to the function signature.
 *
 * @global Hooks[] $hooks         Stores all of the filters and actions.
 * @global int[]     $nhooks        Stores the number of times each filter was triggered.
 * @global string[]  $current_filter Stores the list of current filters with the current one last.
 *
 * @param string $hook_name The name of the filter hook.
 * @param mixed  $value     The value to filter.
 * @param mixed  ...$args   Additional parameters to pass to the callback functions.
 * @return mixed The filtered value after all hooked functions are applied to it.
 */
//function apply_filters( $hook_name, $value, ...$args ) {
function apply_hook( $hook_name, $value, ...$args ) {
	global $hooks, $nhooks, $current_filter;

	if (!isset( $nhooks[$hook_name] )) {
		$nhooks[$hook_name] = 1;
	} else {
		++$nhooks[$hook_name];
	}

	// Do 'all' actions first.
	if (isset( $hooks['all'] )) {
		$current_filter[] = $hook_name;

		$all_args = func_get_args(); // phpcs:ignore PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue.NeedsInspection
		_call_all_hook( $all_args );
	}

	if (!isset( $hooks[$hook_name] )) {
		if (isset( $hooks['all'] )) {
			array_pop( $current_filter );
		}

		return $value;
	}

	if (!isset( $hooks['all'] )) {
		$current_filter[] = $hook_name;
	}

	// Pass the value to Hooks.
	array_unshift( $args, $value );

	$filtered = $hooks[$hook_name]->apply( $value, $args );

	array_pop( $current_filter );

	return $filtered;
}

/**
 * Calls the callback functions that have been added to a filter hook, specifying arguments in an array.
 *
 * @since 3.0.0
 *
 * @see apply_filters() This function is identical, but the arguments passed to the
 *                      functions hooked to `$hook_name` are supplied using an array.
 *
 * @global Hooks[] $hooks         Stores all of the filters and actions.
 * @global int[]     $nhooks        Stores the number of times each filter was triggered.
 * @global string[]  $current_filter Stores the list of current filters with the current one last.
 *
 * @param string $hook_name The name of the filter hook.
 * @param array  $args      The arguments supplied to the functions hooked to `$hook_name`.
 * @return mixed The filtered value after all hooked functions are applied to it.
 */
function apply_filters_ref_array( $hook_name, $args ) {
	global $hooks, $nhooks, $current_filter;

	if (!isset( $nhooks[$hook_name] )) {
		$nhooks[$hook_name] = 1;
	} else {
		++$nhooks[$hook_name];
	}

	// Do 'all' actions first.
	if (isset( $hooks['all'] )) {
		$current_filter[] = $hook_name;
		$all_args            = func_get_args(); // phpcs:ignore PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue.NeedsInspection
		_call_all_hook( $all_args );
	}

	if (!isset( $hooks[$hook_name] )) {
		if (isset( $hooks['all'] )) {
			array_pop( $current_filter );
		}

		return $args[0];
	}

	if (!isset( $hooks['all'] )) {
		$current_filter[] = $hook_name;
	}

	$filtered = $hooks[$hook_name]->apply( $args[0], $args );

	array_pop( $current_filter );

	return $filtered;
}

/**
 * Checks if any filter has been registered for a hook.
 *
 * When using the `$callback` argument, this function may return a non-boolean value
 * that evaluates to false (e.g. 0), so use the `===` operator for testing the return value.
 *
 * @since 2.5.0
 *
 * @global Hooks[] $hooks Stores all of the filters and actions.
 *
 * @param string                      $hook_name The name of the filter hook.
 * @param callable|string|array|false $callback  Optional. The callback to check for.
 *                                               This function can be called unconditionally to speculatively check
 *                                               a callback that may or may not exist. Default false.
 * @return bool|int If `$callback` is omitted, returns boolean for whether the hook has
 *                  anything registered. When checking a specific function, the priority
 *                  of that hook is returned, or false if the function is not attached.
 */
//function has_filter( $hook_name, $callback = false ) {
function has_hook( $hook_name, $callback = false ) {
	global $hooks;

	if (!isset( $hooks[$hook_name] )) {
		return false;
	}

	return $hooks[$hook_name]->has( $hook_name, $callback );
}

/**
 * Removes a callback function from a filter hook.
 *
 * This can be used to remove default functions attached to a specific filter
 * hook and possibly replace them with a substitute.
 *
 * To remove a hook, the `$callback` and `$priority` arguments must match
 * when the hook was added. This goes for both filters and actions. No warning
 * will be given on removal failure.
 *
 * @since 1.2.0
 *
 * @global Hooks[] $hooks Stores all of the filters and actions.
 *
 * @param string                $hook_name The filter hook to which the function to be removed is hooked.
 * @param callable|string|array $callback  The callback to be removed from running when the filter is applied.
 *                                         This function can be called unconditionally to speculatively remove
 *                                         a callback that may or may not exist.
 * @param int                   $priority  Optional. The exact priority used when adding the original
 *                                         filter callback. Default 10.
 * @return bool Whether the function existed before it was removed.
 */
//function remove_filter( $hook_name, $callback, $priority = 10 ) {
function remove_hook( $hook_name, $callback, $priority = 10 ) {
	global $hooks;

	$r = false;

	if (isset( $hooks[$hook_name] )) {
		$r = $hooks[$hook_name]->remove( $hook_name, $callback, $priority );

		if (!$hooks[$hook_name]->callbacks) {
			unset( $hooks[$hook_name] );
		}
	}

	return $r;
}

/**
 * Removes all of the callback functions from a filter hook.
 *
 * @since 2.7.0
 *
 * @global Hooks[] $hooks Stores all of the filters and actions.
 *
 * @param string    $hook_name The filter to remove callbacks from.
 * @param int|false $priority  Optional. The priority number to remove them from.
 *                             Default false.
 * @return true Always returns true.
 */
function remove_all_filters( $hook_name, $priority = false ) {
	global $hooks;

	if (isset( $hooks[$hook_name] )) {
		$hooks[$hook_name]->remove_all_filters( $priority );

		if (!$hooks[$hook_name]->has_filters()) {
			unset( $hooks[$hook_name] );
		}
	}

	return true;
}

/**
 * Retrieves the name of the current filter hook.
 *
 * @since 2.5.0
 *
 * @global string[] $current_filter Stores the list of current filters with the current one last
 *
 * @return string Hook name of the current filter.
 */
function current_filter() {
	global $current_filter;

	return end( $current_filter );
}

/**
 * Returns whether or not a filter hook is currently being processed.
 *
 * The function current_filter() only returns the most recent filter being executed.
 * did_filter() returns the number of times a filter has been applied during
 * the current request.
 *
 * This function allows detection for any filter currently being executed
 * (regardless of whether it's the most recent filter to fire, in the case of
 * hooks called from hook callbacks) to be verified.
 *
 * @since 3.9.0
 *
 * @see current_filter()
 * @see did_filter()
 * @global string[] $current_filter Current filter.
 *
 * @param string|null $hook_name Optional. Filter hook to check. Defaults to null,
 *                               which checks if any filter is currently being run.
 * @return bool Whether the filter is currently in the stack.
 */
function doing_filter( $hook_name = null ) {
	global $current_filter;

	if (null === $hook_name) {
		return !empty( $current_filter );
	}

	return in_array( $hook_name, $current_filter, true );
}

/**
 * Retrieves the number of times a filter has been applied during the current request.
 *
 * @since 6.1.0
 *
 * @global int[] $nhooks Stores the number of times each filter was triggered.
 *
 * @param string $hook_name The name of the filter hook.
 * @return int The number of times the filter hook has been applied.
 */
function did_filter( $hook_name ) {
	global $nhooks;

	if (!isset( $nhooks[$hook_name] )) {
		return 0;
	}

	return $nhooks[$hook_name];
}

/**
 * Adds a callback function to an action hook.
 *
 * Actions are the hooks that the WordPress core launches at specific points
 * during execution, or when specific events occur. Plugins can specify that
 * one or more of its PHP functions are executed at these points, using the
 * Action API.
 *
 * @since 1.2.0
 *
 * @param string   $hook_name       The name of the action to add the callback to.
 * @param callable $callback        The callback to be run when the action is called.
 * @param int      $priority        Optional. Used to specify the order in which the functions
 *                                  associated with a particular action are executed.
 *                                  Lower numbers correspond with earlier execution,
 *                                  and functions with the same priority are executed
 *                                  in the order in which they were added to the action. Default 10.
 * @param int      $accepted_args   Optional. The number of arguments the function accepts. Default 1.
 * @return true Always returns true.
 */
function add_action( $hook_name, $callback, $priority = 10, $accepted_args = 1 ) {
	return add_hook( $hook_name, $callback, $priority, $accepted_args );
}

/**
 * Calls the callback functions that have been added to an action hook.
 *
 * This function invokes all functions attached to action hook `$hook_name`.
 * It is possible to create new action hooks by simply calling this function,
 * specifying the name of the new hook using the `$hook_name` parameter.
 *
 * You can pass extra arguments to the hooks, much like you can with `apply_filters()`.
 *
 * Example usage:
 *
 *     // The action callback function.
 *     function example_callback( $arg1, $arg2 ) {
 *         // (maybe) do something with the args.
 *     }
 *     add_action( 'example_action', 'example_callback', 10, 2 );
 *
 *     /*
 *      * Trigger the actions by calling the 'example_callback()' function
 *      * that's hooked onto `example_action` above.
 *      *
 *      * - 'example_action' is the action hook.
 *      * - $arg1 and $arg2 are the additional arguments passed to the callback.
 *     do_action( 'example_action', $arg1, $arg2 );
 *
 * @since 1.2.0
 * @since 5.3.0 Formalized the existing and already documented `...$arg` parameter
 *              by adding it to the function signature.
 *
 * @global Hooks[] $hooks         Stores all of the filters and actions.
 * @global int[]     $actions        Stores the number of times each action was triggered.
 * @global string[]  $current_filter Stores the list of current filters with the current one last.
 *
 * @param string $hook_name The name of the action to be executed.
 * @param mixed  ...$arg    Optional. Additional arguments which are passed on to the
 *                          functions hooked to the action. Default empty.
 */
function do_action( $hook_name, ...$arg ) {
	global $hooks, $actions, $current_filter;

	if (!isset( $actions[$hook_name] )) {
		$actions[$hook_name] = 1;
	} else {
		++$actions[$hook_name];
	}

	// Do 'all' actions first.
	if (isset( $hooks['all'] )) {
		$current_filter[] = $hook_name;
		$all_args         = func_get_args(); // phpcs:ignore PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue.NeedsInspection
		_call_all_hook( $all_args );
	}

	if (!isset( $hooks[$hook_name] )) {
		if (isset( $hooks['all'] )) {
			array_pop( $current_filter );
		}

		return;
	}

	if (!isset( $hooks['all'] )) {
		$current_filter[] = $hook_name;
	}

	if (empty( $arg )) {
		$arg[] = '';
	} elseif (is_array( $arg[0] ) && 1 === count( $arg[0] ) && isset( $arg[0][0] ) && is_object( $arg[0][0] )) {
		// Backward compatibility for PHP4-style passing of `array( &$this )` as action `$arg`.
		$arg[0] = $arg[0][0];
	}

//	$hooks[$hook_name]->do_action( $arg );

//	array_pop( $current_filter );
	array_pop( $current_filter );
	return $hooks[$hook_name]->do_action( $arg );
}

/**
 * Calls the callback functions that have been added to an action hook, specifying arguments in an array.
 *
 * @since 2.1.0
 *
 * @see do_action() This function is identical, but the arguments passed to the
 *                  functions hooked to `$hook_name` are supplied using an array.
 *
 * @global Hooks[] $hooks         Stores all of the filters and actions.
 * @global int[]     $actions        Stores the number of times each action was triggered.
 * @global string[]  $current_filter Stores the list of current filters with the current one last.
 *
 * @param string $hook_name The name of the action to be executed.
 * @param array  $args      The arguments supplied to the functions hooked to `$hook_name`.
 */
function do_action_ref_array( $hook_name, $args ) {
	global $hooks, $actions, $current_filter;

	if (!isset( $actions[$hook_name] )) {
		$actions[$hook_name] = 1;
	} else {
		++$actions[$hook_name];
	}

	// Do 'all' actions first.
	if (isset( $hooks['all'] )) {
		$current_filter[] = $hook_name;
		$all_args            = func_get_args(); // phpcs:ignore PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue.NeedsInspection
		_call_all_hook( $all_args );
	}

	if (!isset( $hooks[$hook_name] )) {
		if (isset( $hooks['all'] )) {
			array_pop( $current_filter );
		}

		return;
	}

	if (!isset( $hooks['all'] )) {
		$current_filter[] = $hook_name;
	}

	$hooks[$hook_name]->do_action( $args );

	array_pop( $current_filter );
}

/**
 * Checks if any action has been registered for a hook.
 *
 * When using the `$callback` argument, this function may return a non-boolean value
 * that evaluates to false (e.g. 0), so use the `===` operator for testing the return value.
 *
 * @since 2.5.0
 *
 * @see has_filter() has_action() is an alias of has_filter().
 *
 * @param string                      $hook_name The name of the action hook.
 * @param callable|string|array|false $callback  Optional. The callback to check for.
 *                                               This function can be called unconditionally to speculatively check
 *                                               a callback that may or may not exist. Default false.
 * @return bool|int If `$callback` is omitted, returns boolean for whether the hook has
 *                  anything registered. When checking a specific function, the priority
 *                  of that hook is returned, or false if the function is not attached.
 */
function has_action( $hook_name, $callback = false ) {
	return has_filter( $hook_name, $callback );
}

/**
 * Removes a callback function from an action hook.
 *
 * This can be used to remove default functions attached to a specific action
 * hook and possibly replace them with a substitute.
 *
 * To remove a hook, the `$callback` and `$priority` arguments must match
 * when the hook was added. This goes for both filters and actions. No warning
 * will be given on removal failure.
 *
 * @since 1.2.0
 *
 * @param string                $hook_name The action hook to which the function to be removed is hooked.
 * @param callable|string|array $callback  The name of the function which should be removed.
 *                                         This function can be called unconditionally to speculatively remove
 *                                         a callback that may or may not exist.
 * @param int                   $priority  Optional. The exact priority used when adding the original
 *                                         action callback. Default 10.
 * @return bool Whether the function is removed.
 */
function remove_action( $hook_name, $callback, $priority = 10 ) {
	return remove_filter( $hook_name, $callback, $priority );
}

/**
 * Removes all of the callback functions from an action hook.
 *
 * @since 2.7.0
 *
 * @param string    $hook_name The action to remove callbacks from.
 * @param int|false $priority  Optional. The priority number to remove them from.
 *                             Default false.
 * @return true Always returns true.
 */
function remove_all_actions( $hook_name, $priority = false ) {
	return remove_all_filters( $hook_name, $priority );
}

/**
 * Retrieves the name of the current action hook.
 *
 * @since 3.9.0
 *
 * @return string Hook name of the current action.
 */
function current_action() {
	return current_filter();
}

/**
 * Returns whether or not an action hook is currently being processed.
 *
 * The function current_action() only returns the most recent action being executed.
 * did_action() returns the number of times an action has been fired during
 * the current request.
 *
 * This function allows detection for any action currently being executed
 * (regardless of whether it's the most recent action to fire, in the case of
 * hooks called from hook callbacks) to be verified.
 *
 * @since 3.9.0
 *
 * @see current_action()
 * @see did_action()
 *
 * @param string|null $hook_name Optional. Action hook to check. Defaults to null,
 *                               which checks if any action is currently being run.
 * @return bool Whether the action is currently in the stack.
 */
function doing_action( $hook_name = null ) {
	return doing_filter( $hook_name );
}

/**
 * Retrieves the number of times an action has been fired during the current request.
 *
 * @since 2.1.0
 *
 * @global int[] $actions Stores the number of times each action was triggered.
 *
 * @param string $hook_name The name of the action hook.
 * @return int The number of times the action hook has been fired.
 */
function did_action( $hook_name ) {
	global $actions;

	if (!isset( $actions[$hook_name] )) {
		return 0;
	}

	return $actions[$hook_name];
}

/**
 * Fires functions attached to a deprecated filter hook.
 *
 * When a filter hook is deprecated, the apply_filters() call is replaced with
 * apply_filters_deprecated(), which triggers a deprecation notice and then fires
 * the original filter hook.
 *
 * Note: the value and extra arguments passed to the original apply_filters() call
 * must be passed here to `$args` as an array. For example:
 *
 *     // Old filter.
 *     return apply_filters( 'wpdocs_filter', $value, $extra_arg );
 *
 *     // Deprecated.
 *     return apply_filters_deprecated( 'wpdocs_filter', array( $value, $extra_arg ), '4.9.0', 'wpdocs_new_filter' );
 *
 * @since 4.6.0
 *
 * @see _deprecated_hook()
 *
 * @param string $hook_name   The name of the filter hook.
 * @param array  $args        Array of additional function arguments to be passed to apply_filters().
 * @param string $version     The version of WordPress that deprecated the hook.
 * @param string $replacement Optional. The hook that should have been used. Default empty.
 * @param string $message     Optional. A message regarding the change. Default empty.
 */
function apply_filters_deprecated( $hook_name, $args, $version, $replacement = '', $message = '' ) {
	if (!has_filter( $hook_name )) {
		return $args[0];
	}

	_deprecated_hook( $hook_name, $version, $replacement, $message );

	return apply_filters_ref_array( $hook_name, $args );
}

/**
 * Fires functions attached to a deprecated action hook.
 *
 * When an action hook is deprecated, the do_action() call is replaced with
 * do_action_deprecated(), which triggers a deprecation notice and then fires
 * the original hook.
 *
 * @since 4.6.0
 *
 * @see _deprecated_hook()
 *
 * @param string $hook_name   The name of the action hook.
 * @param array  $args        Array of additional function arguments to be passed to do_action().
 * @param string $version     The version of WordPress that deprecated the hook.
 * @param string $replacement Optional. The hook that should have been used. Default empty.
 * @param string $message     Optional. A message regarding the change. Default empty.
 */
function do_action_deprecated( $hook_name, $args, $version, $replacement = '', $message = '' ) {
	if (!has_action( $hook_name )) {
		return;
	}

	_deprecated_hook( $hook_name, $version, $replacement, $message );

	do_action_ref_array( $hook_name, $args );
}


/**
 * Calls the 'all' hook, which will process the functions hooked into it.
 *
 * The 'all' hook passes all of the arguments or parameters that were used for
 * the hook, which this function was called for.
 *
 * This function is used internally for apply_filters(), do_action(), and
 * do_action_ref_array() and is not meant to be used from outside those
 * functions. This function does not check for the existence of the all hook, so
 * it will fail unless the all hook exists prior to this function call.
 *
 * @since 2.5.0
 * @access private
 *
 * @global Hooks[] $hooks Stores all of the filters and actions.
 *
 * @param array $args The collected parameters from the hook that was called.
 */
function _call_all_hook( $args ) {
	global $hooks;

	$hooks['all']->do_all_hook( $args );
}

function show_hooks()
{
	global $hooks;
	return isset($hooks['all']) ? $hooks['all']->show() : print_r($hooks, true);//['']->show();
}
