<?php
namespace Yuma\HTML;
class AForm {
	public const ELEMENTS = Array(
		Array(
			'tag' => 'p',
			'text' => 'Plain text',
//			'display' => sprintf( t('_%s Plain text'), '¶'),//&#xb6; HEX CODE &#182; HTML CODE &para; HTML ENTITY \00B6
			'display' => 'ℙ',//DOUBLE-STRUCK CAPITAL P ℙ U+02119 UNICODE &#x2119; HEX CODE &#8473; HTML CODE &Popf; HTML ENTITY \2119
			'value' => '',	// if input tag puts **** in ... value="****">, otherwise ...>****</...
		),
		Array(
			'tag' => 'label',
			'text' => 'Label for an input',
//			'display' => '∥ abc...',//U+02225 UNICODE &#x2225; HEX CODE &#8741; HTML CODE &parallel; HTML ENTITY \2225
//			'display' => '≬ abc...',//BETWEEN ≬ U+0226C UNICODE &#x226C; HEX CODE &#8812; HTML CODE &between; HTML ENTITY \226C
			'display' => '⊑ abc'.//SQUARE IMAGE OF OR EQUAL TO ⊑ U+02291 UNICODE &#x2291; HEX CODE &#8849; HTML CODE &sqsube; HTML ENTITY \2291
			' ⊒',//SQUARE ORIGINAL OF OR EQUAL TO ⊒ U+02292 UNICODE &#x2292; HEX CODE &#8850; HTML CODE &sqsupe; HTML ENTITY \2292
		),
		Array(
			'tag' => 'div',
			'text' => 'Group of items',
			'display' => '⚝'//U+0269D UNICODE &#x269D; HEX CODE &#9885; HTML CODE \269D
		),
		Array(
			'tag' => "input type='text'",
			'text' => 'Input box - text',
			'display' => '⇲ abc',//U+021F2 UNICODE &#x21f2; HEX CODE &#8690; HTML CODE \21F2
		),
		Array(
			'tag' => "input type='number'",
			'text' => 'Input box - number',
			'display' => '⇲ 012...',//U+021F2 UNICODE &#x21f2; HEX CODE &#8690; HTML CODE \21F2
		),
		Array(
			'tag' => "input type='tel'",
			'text' => 'Input box - telephone number',
			'display' => '⇲ ℡',//U+021F2 UNICODE &#x21f2; HEX CODE &#8690; HTML CODE \21F2  U+02121 UNICODE &#x2121; HEX CODE &#8481; HTML CODE \2121
		),
		Array(
			'tag' => "input type='checkbox'",
			'text' => 'Input box - checkbox',
			'display' => '⇲ ☑',//U+021F2 UNICODE &#x21f2; HEX CODE &#8690; HTML CODE \21F2  U+02611 UNICODE &#x2611; HEX CODE &#9745; HTML CODE \2611i
			//'⊠'//SQUARED TIMES ⊠ U+022A0 UNICODE &#x22A0; HEX CODE &#8864; HTML CODE &timesb; HTML ENTITY \22A0
		),
		Array(
			'tag' => "input type='radio'",
			'text' => 'Input box - radio button',
			'display' => '⇲ ☉',//U+021F2 UNICODE &#x21f2; HEX CODE &#8690; HTML CODE \21F2  SUN ☉ U+02609 UNICODE &#x2609; HEX CODE &#9737; HTML CODE \2609
			//'⊙'//CIRCLED DOT OPERATOR ⊙ U+02299 UNICODE &#x2299; HEX CODE &#8857; HTML CODE &odot; HTML ENTITY \2299
		),
	);
	public const STYLES = Array(
		Array(
			'name' => 'bold',
			'display' => '<b>b</b>',
			'css' => 'font-weight: bold;',
		),
		Array(
			'name' => 'italic',
			'display' => '<i>i</i>',
			'css' => 'font-style: italic;',
		),
		Array(
			'name' => 'underline',
			'display' => '<span style="text-decoration: underline">u</span>',
			'css' => 'text-decoration: underline;',
		),
		Array(
			'title' => 'colour',
			'css' => 'color: %%;',
		),
		Array(
			'title' => 'background color',
			'css' => 'background-color: %%;',
		),
	);


}
