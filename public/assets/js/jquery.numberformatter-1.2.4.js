﻿/**
 * jquery.numberformatter - Formatting/Parsing Numbers in jQuery
 * 
 * Written by
 * Michael Abernethy (mike@abernethysoft.com),
 * Andrew Parry (aparry0@gmail.com)
 *
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * @author Michael Abernethy, Andrew Parry
 * @version 1.2.4-RELEASE ($Id$)
 * 
 * Dependencies
 * 
 * jQuery (http://jquery.com)
 * jshashtable (http://www.timdown.co.uk/jshashtable)
 * 
 * Notes & Thanks
 * 
 * many thanks to advweb.nanasi.jp for his bug fixes
 * jsHashtable is now used also, so thanks to the author for that excellent little class.
 *
 * This plugin can be used to format numbers as text and parse text as Numbers
 * Because we live in an international world, we cannot assume that everyone
 * uses "," to divide thousands, and "." as a decimal point.
 *
 * As of 1.2 the way this plugin works has changed slightly, parsing text to a number
 * has 1 set of functions, formatting a number to text has it's own. Before things
 * were a little confusing, so I wanted to separate the 2 out more.
 *
 *
 * jQuery extension functions:
 *
 * formatNumber(options, writeBack, giveReturnValue) - Reads the value from the subject, parses to
 * a Javascript Number object, then formats back to text using the passed options and write back to
 * the subject.
 * 
 * parseNumber(options) - Parses the value in the subject to a Number object using the passed options
 * to decipher the actual number from the text, then writes the value as text back to the subject.
 * 
 * 
 * Generic functions:
 * 
 * formatNumber(numberString, options) - Takes a plain number as a string (e.g. '1002.0123') and returns
 * a string of the given format options.
 * 
 * parseNumber(numberString, options) - Takes a number as text that is formatted the same as the given
 * options then and returns it as a plain Number object.
 * 
 * To achieve the old way of combining parsing and formatting to keep say a input field always formatted
 * to a given format after it has lost focus you'd simply use a combination of the functions.
 * 
 * e.g.
 * $("#salary").blur(function(){
 *      $(this).parseNumber({format:"#,###.00", locale:"us"});
 *      $(this).formatNumber({format:"#,###.00", locale:"us"});
 * });
 *
 * The syntax for the formatting is:
 * 0 = Digit
 * # = Digit, zero shows as absent
 * . = Decimal separator
 * - = Negative sign
 * , = Grouping Separator
 * % = Percent (multiplies the number by 100)
 * 
 * For example, a format of "#,###.00" and text of 4500.20 will
 * display as "4.500,20" with a locale of "de", and "4,500.20" with a locale of "us"
 *
 *
 * As of now, the only acceptable locales are 
 * Arab Emirates -> "ae"
 * Australia -> "au"
 * Austria -> "at"
 * Brazil -> "br"
 * Canada -> "ca"
 * China -> "cn"
 * Czech -> "cz"
 * Denmark -> "dk"
 * Egypt -> "eg"
 * Finland -> "fi"
 * France  -> "fr"
 * Germany -> "de"
 * Greece -> "gr"
 * Great Britain -> "gb"
 * Hong Kong -> "hk"
 * India -> "in"
 * Israel -> "il"
 * Japan -> "jp"
 * Russia -> "ru"
 * South Korea -> "kr"
 * Spain -> "es"
 * Sweden -> "se"
 * Switzerland -> "ch"
 * Taiwan -> "tw"
 * Thailand -> "th"
 * United States -> "us"
 * Vietnam -> "vn"
 **/

(function($) {

    var nfLocales = new Hashtable();
    
    var nfLocalesLikeUS = [ 'ae','au','ca','cn','eg','gb','hk','il','in','jp','sk','th','tw','us' ];
    var nfLocalesLikeDE = [ 'at','br','de','dk','es','gr','it','nl','pt','tr','vn' ];
    var nfLocalesLikeFR = [ 'bg','cz','fi','fr','no','pl','ru','se' ];
    var nfLocalesLikeCH = [ 'ch' ];
    
    var nfLocaleFormatting = [ [".", ","], [",", "."], [",", " "], [".", "'"] ]; 
    var nfAllLocales = [ nfLocalesLikeUS, nfLocalesLikeDE, nfLocalesLikeFR, nfLocalesLikeCH ]

    function FormatData(dec, group, neg) {
        this.dec = dec;
        this.group = group;
        this.neg = neg;
    };

    function init() {
        // write the arrays into the hashtable
        for (var localeGroupIdx = 0; localeGroupIdx < nfAllLocales.length; localeGroupIdx++) {
            var localeGroup = nfAllLocales[localeGroupIdx];
            for (var i = 0; i < localeGroup.length; i++) {
                nfLocales.put(localeGroup[i], localeGroupIdx);
            }
        }
    };

    function formatCodes(locale, isFullLocale) {
        if (nfLocales.size() == 0)
            init();

         // default values
         var dec = ".";
         var group = ",";
         var neg = "-";
         
         if (isFullLocale == false) {
             // Extract and convert to lower-case any language code from a real 'locale' formatted string, if not use as-is
             // (To prevent locale format like : "fr_FR", "en_US", "de_DE", "fr_FR", "en-US", "de-DE")
             if (locale.indexOf('_') != -1)
                locale = locale.split('_')[1].toLowerCase();
             else if (locale.indexOf('-') != -1)
                locale = locale.split('-')[1].toLowerCase();
        }

         // hashtable lookup to match locale with codes
         var codesIndex = nfLocales.get(locale);
         if (codesIndex) {
            var codes = nfLocaleFormatting[codesIndex];
            if (codes) {
                dec = codes[0];
                group = codes[1];
            }
         }
         return new FormatData(dec, group, neg);
    };
    
    
    /*  Formatting Methods  */
    
    
    /**
     * Formats anything containing a number in standard js number notation.
     * 
     * @param {Object}  options         The formatting options to use
     * @param {Boolean} writeBack       (true) If the output value should be written back to the subject
     * @param {Boolean} giveReturnValue (true) If the function should return the output string
     */
    $.fn.formatNumber = function(options, writeBack, giveReturnValue) {
    
        return this.each(function() {
            // enforce defaults
            if (writeBack == null)
                writeBack = true;
            if (giveReturnValue == null)
                giveReturnValue = true;
            
            // get text
            var text;
            if ($(this).is(":input"))
                text = new String($(this).val());
            else
                text = new String($(this).text());

            // format
            var returnString = $.formatNumber(text, options);
        
            // set formatted string back, only if a success
//          if (returnString) {
                if (writeBack) {
                    if ($(this).is(":input"))
                        $(this).val(returnString);
                    else
                        $(this).text(returnString);
                }
                if (giveReturnValue)
                    return returnString;
//          }
//          return '';
        });
    };
    
    /**
     * First parses a string and reformats it with the given options.
     * 
     * @param {Object} numberString
     * @param {Object} options
     */
    $.formatNumber = function(numberString, options){
        var options = $.extend({}, $.fn.formatNumber.defaults, options);
        var formatData = formatCodes(options.locale.toLowerCase(), options.isFullLocale);
        
        var dec = formatData.dec;
        var group = formatData.group;
        var neg = formatData.neg;
        
        var validFormat = "0#-,.";
        
        // strip all the invalid characters at the beginning and the end
        // of the format, and we'll stick them back on at the end
        // make a special case for the negative sign "-" though, so 
        // we can have formats like -$23.32
        var prefix = "";
        var negativeInFront = false;
        for (var i = 0; i < options.format.length; i++) {
            if (validFormat.indexOf(options.format.charAt(i)) == -1) 
                prefix = prefix + options.format.charAt(i);
            else 
                if (i == 0 && options.format.charAt(i) == '-') {
                    negativeInFront = true;
                    continue;
                }
                else 
                    break;
        }
        var suffix = "";
        for (var i = options.format.length - 1; i >= 0; i--) {
            if (validFormat.indexOf(options.format.charAt(i)) == -1) 
                suffix = options.format.charAt(i) + suffix;
            else 
                break;
        }
        
        options.format = options.format.substring(prefix.length);
        options.format = options.format.substring(0, options.format.length - suffix.length);
        
        // now we need to convert it into a number
        //while (numberString.indexOf(group) > -1) 
        //  numberString = numberString.replace(group, '');
        //var number = new Number(numberString.replace(dec, ".").replace(neg, "-"));
        var number = new Number(numberString);
        
        return $._formatNumber(number, options, suffix, prefix, negativeInFront);
    };
    
    /**
     * Formats a Number object into a string, using the given formatting options
     * 
     * @param {Object} numberString
     * @param {Object} options
     */
    $._formatNumber = function(number, options, suffix, prefix, negativeInFront) {
        var options = $.extend({}, $.fn.formatNumber.defaults, options);
        var formatData = formatCodes(options.locale.toLowerCase(), options.isFullLocale);
        
        var dec = formatData.dec;
        var group = formatData.group;
        var neg = formatData.neg;

        // check overrides
        if (options.overrideGroupSep != null) {
            group = options.overrideGroupSep;
        }
        if (options.overrideDecSep != null) {
            dec = options.overrideDecSep;
        }
        if (options.overrideNegSign != null) {
            neg = options.overrideNegSign;
        }
        
        // Check NAN handling
        var forcedToZero = false;
        if (isNaN(number)) {
            if (options.nanForceZero == true) {
                number = 0;
                forcedToZero = true;
            } else {
                return '';
            }
        }

        // special case for percentages
        if (options.isPercentage == true || (options.autoDetectPercentage && suffix.charAt(suffix.length - 1) == '%')) {
            number = number * 100;
        }

        var returnString = "";
        if (options.format.indexOf(".") > -1) {
            var decimalPortion = dec;
            var decimalFormat = options.format.substring(options.format.lastIndexOf(".") + 1);
            
            // round or truncate number as needed
            if (options.round == true)
                number = new Number(number.toFixed(decimalFormat.length));
            else {
                var numStr = number.toString();
                if (numStr.lastIndexOf('.') > 0) {
                    numStr = numStr.substring(0, numStr.lastIndexOf('.') + decimalFormat.length + 1);
                }
                number = new Number(numStr);
            }
            
            var decimalValue = new Number(number.toString().substring(number.toString().indexOf('.')));
            decimalString = new String(decimalValue.toFixed(decimalFormat.length));
            decimalString = decimalString.substring(decimalString.lastIndexOf('.') + 1);
            for (var i = 0; i < decimalFormat.length; i++) {
                if (decimalFormat.charAt(i) == '#' && decimalString.charAt(i) != '0') {
                    decimalPortion += decimalString.charAt(i);
                    continue;
                } else if (decimalFormat.charAt(i) == '#' && decimalString.charAt(i) == '0') {
                    var notParsed = decimalString.substring(i);
                    if (notParsed.match('[1-9]')) {
                        decimalPortion += decimalString.charAt(i);
                        continue;
                    } else
                        break;
                } else if (decimalFormat.charAt(i) == "0")
                    decimalPortion += decimalString.charAt(i);
            }
            returnString += decimalPortion
         } else
            number = Math.round(number);

        var ones = Math.floor(number);
        if (number < 0)
            ones = Math.ceil(number);

        var onesFormat = "";
        if (options.format.indexOf(".") == -1)
            onesFormat = options.format;
        else
            onesFormat = options.format.substring(0, options.format.indexOf("."));

        var onePortion = "";
        if (!(ones == 0 && onesFormat.substr(onesFormat.length - 1) == '#') || forcedToZero) {
            // find how many digits are in the group
            var oneText = new String(Math.abs(ones));
            var groupLength = 9999;
            if (onesFormat.lastIndexOf(",") != -1)
                groupLength = onesFormat.length - onesFormat.lastIndexOf(",") - 1;
            var groupCount = 0;
            for (var i = oneText.length - 1; i > -1; i--) {
                onePortion = oneText.charAt(i) + onePortion;
                groupCount++;
                if (groupCount == groupLength && i != 0) {
                    onePortion = group + onePortion;
                    groupCount = 0;
                }
            }
            
            // account for any pre-data padding
            if (onesFormat.length > onePortion.length) {
                var padStart = onesFormat.indexOf('0');
                if (padStart != -1) {
                    var padLen = onesFormat.length - padStart;
                    
                    // pad to left with 0's or group char
                    var pos = onesFormat.length - onePortion.length - 1;
                    while (onePortion.length < padLen) {
                        var padChar = onesFormat.charAt(pos);
                        // replace with real group char if needed
                        if (padChar == ',')
                            padChar = group;
                        onePortion = padChar + onePortion;
                        pos--;
                    }
                }
            }
        }
        
        if (!onePortion && onesFormat.indexOf('0', onesFormat.length - 1) !== -1)
            onePortion = '0';

        returnString = onePortion + returnString;

        // handle special case where negative is in front of the invalid characters
        if (number < 0 && negativeInFront && prefix.length > 0)
            prefix = neg + prefix;
        else if (number < 0)
            returnString = neg + returnString;
        
        if (!options.decimalSeparatorAlwaysShown) {
            if (returnString.lastIndexOf(dec) == returnString.length - 1) {
                returnString = returnString.substring(0, returnString.length - 1);
            }
        }
        returnString = prefix + returnString + suffix;
        return returnString;
    };


    /*  Parsing Methods */


    /**
     * Parses a number of given format from the element and returns a Number object.
     * @param {Object} options
     */
    $.fn.parseNumber = function(options, writeBack, giveReturnValue) {
        // enforce defaults
        if (writeBack == null)
            writeBack = true;
        if (giveReturnValue == null)
            giveReturnValue = true;
        
        // get text
        var text;
        if ($(this).is(":input"))
            text = new String($(this).val());
        else
            text = new String($(this).text());
    
        // parse text
        var number = $.parseNumber(text, options);
        
        if (number) {
            if (writeBack) {
                if ($(this).is(":input"))
                    $(this).val(number.toString());
                else
                    $(this).text(number.toString());
            }
            if (giveReturnValue)
                return number;
        }
    };
    
    /**
     * Parses a string of given format into a Number object.
     * 
     * @param {Object} string
     * @param {Object} options
     */
    $.parseNumber = function(numberString, options) {
        var options = $.extend({}, $.fn.parseNumber.defaults, options);
        var formatData = formatCodes(options.locale.toLowerCase(), options.isFullLocale);

        var dec = formatData.dec;
        var group = formatData.group;
        var neg = formatData.neg;

        // check overrides
        if (options.overrideGroupSep != null) {
            group = options.overrideGroupSep;
        }
        if (options.overrideDecSep != null) {
            dec = options.overrideDecSep;
        }
        if (options.overrideNegSign != null) {
            neg = options.overrideNegSign;
        }

        var valid = "1234567890";
        var validOnce = '.-';
        var strictMode = options.strict;
        
        // now we need to convert it into a number
        while (numberString.indexOf(group)>-1) {
            numberString = numberString.replace(group, '');
        }
        numberString = numberString.replace(dec, '.').replace(neg, '-');
        var validText = '';
        var hasPercent = false;

        if (options.isPercentage == true || (options.autoDetectPercentage && numberString.charAt(numberString.length - 1) == '%')) {
            hasPercent = true;
        }

        for (var i = 0; i < numberString.length; i++) {
            if (valid.indexOf(numberString.charAt(i)) > -1) {
                validText = validText + numberString.charAt(i);
            } else if (validOnce.indexOf(numberString.charAt(i)) > -1) {
                validText = validText + numberString.charAt(i);
                validOnce = validOnce.replace(numberString.charAt(i), '');
            } else {
                if (options.allowPostfix) {
                    // treat anything after this point (inclusive) as a postfix
                    break;
                } else if (strictMode) {
                    // abort and force the text to NaN as it's not strictly valid
                    validText = 'NaN';
                    break;
                }
            }
        }
        var number = new Number(validText);
        if (hasPercent) {
            number = number / 100;
            var decimalPos = validText.indexOf('.');
            if (decimalPos != -1) {
                var decimalPoints = validText.length - decimalPos - 1;
                number = number.toFixed(decimalPoints + 2);
            } else {
                number = number.toFixed(2);
            }
        }

        return number;
    };

    $.fn.parseNumber.defaults = {
        locale: "us",
        decimalSeparatorAlwaysShown: false,
        isPercentage: false,            // treats the input as a percentage (i.e. input divided by 100)
        autoDetectPercentage: true,     // will search if the input string ends with '%', if it does then the above option is implicitly set
        isFullLocale: false,
        strict: false,                  // will abort the parse if it hits any unknown char
        overrideGroupSep: null,         // override for group separator
        overrideDecSep: null,           // override for decimal point separator
        overrideNegSign: null,          // override for negative sign
        allowPostfix: false             // will truncate the input string as soon as it hits an unknown char
    };

    $.fn.formatNumber.defaults = {
        format: "#,###.00",
        locale: "us",
        decimalSeparatorAlwaysShown: false,
        nanForceZero: true,
        round: true,
        isFullLocale: false,
        overrideGroupSep: null,
        overrideDecSep: null,
        overrideNegSign: null,
        isPercentage: false,            // treats the input as a percentage (i.e. input multiplied by 100)
        autoDetectPercentage: true      // will search if the format string ends with '%', if it does then the above option is implicitly set
    };
    
    Number.prototype.toFixed = function(precision) {
        return $._roundNumber(this, precision);
    };
    
    $._roundNumber = function(number, decimalPlaces) {
        var power = Math.pow(10, decimalPlaces || 0);
        var value = String(Math.round(number * power) / power);
        
        // ensure the decimal places are there
        if (decimalPlaces > 0) {
            var dp = value.indexOf(".");
            if (dp == -1) {
                value += '.';
                dp = 0;
            } else {
                dp = value.length - (dp + 1);
            }
            
            while (dp < decimalPlaces) {
                value += '0';
                dp++;
            }
        }
        return value;
    };

 })(jQuery);