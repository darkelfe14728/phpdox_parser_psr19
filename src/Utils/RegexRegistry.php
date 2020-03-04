<?php

namespace phpDoxExtension\Parser\PSR19\Utils;

/**
 * Registry for all regex used
 *
 * @package phpDoxExtension\Parser\PSR19\Utils
 */
abstract class RegexRegistry {
    /**
     * @var string The RFC-2822 email regular expression
     */
    public const EMAIL_RFC2822 = <<<'REGEXP'
/(?(DEFINE)
            (?<addr_spec> (?&local_part) @ (?&domain) )
            (?<local_part> (?&dot_atom) | (?&quoted_string) | (?&obs_local_part) )
            (?<domain> (?&dot_atom) | (?&domain_literal) | (?&obs_domain) )
            (?<domain_literal> (?&CFWS)? \[ (?: (?&FWS)? (?&dtext) )* (?&FWS)? ] (?&CFWS)? )
            (?<dtext> [\x21-\x5a] | [\x5e-\x7e] | (?&obs_dtext) )
            (?<quoted_pair> \ (?: (?&VCHAR) | (?&WSP) ) | (?&obs_qp) )
            (?<dot_atom> (?&CFWS)? (?&dot_atom_text) (?&CFWS)? )
            (?<dot_atom_text> (?&atext) (?: \. (?&atext) )* )
            (?<atext> [a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+ )
            (?<atom> (?&CFWS)? (?&atext) (?&CFWS)? )
            (?<word> (?&atom) | (?&quoted_string) )
            (?<quoted_string> (?&CFWS)? " (?: (?&FWS)? (?&qcontent) )* (?&FWS)? " (?&CFWS)? )
            (?<qcontent> (?&qtext) | (?&quoted_pair) )
            (?<qtext> \x21 | [\x23-\x5b] | [\x5d-\x7e] | (?&obs_qtext) )

            # comments and whitespace
            (?<FWS> (?: (?&WSP)* \r\n )? (?&WSP)+ | (?&obs_FWS) )
            (?<CFWS> (?: (?&FWS)? (?&comment) )+ (?&FWS)? | (?&FWS) )
            (?<comment> \( (?: (?&FWS)? (?&ccontent) )* (?&FWS)? \) )
            (?<ccontent> (?&ctext) | (?&quoted_pair) | (?&comment) )
            (?<ctext> [\x21-\x27] | [\x2a-\x5b] | [\x5d-\x7e] | (?&obs_ctext) )
    
            # obsolete tokens
            (?<obs_domain> (?&atom) (?: \. (?&atom) )* )
            (?<obs_local_part> (?&word) (?: \. (?&word) )* )
            (?<obs_dtext> (?&obs_NO_WS_CTL) | (?&quoted_pair) )
            (?<obs_qp> \ (?: \x00 | (?&obs_NO_WS_CTL) | \n | \r ) )
            (?<obs_FWS> (?&WSP)+ (?: \r\n (?&WSP)+ )* )
            (?<obs_ctext> (?&obs_NO_WS_CTL) )
            (?<obs_qtext> (?&obs_NO_WS_CTL) )
            (?<obs_NO_WS_CTL> [\x01-\x08] | \x0b | \x0c | [\x0e-\x1f] | \x7f )
    
            # character class definitions
            (?<VCHAR> [\x21-\x7E] )
            (?<WSP> [ \t] )
        )
        ^<(?<email>(?&addr_spec))>$/x
REGEXP;

    /**
     * @var string The RFC-2396 URI regular expression
     */
    public const URI_RFC2396 = <<<'REGEXP'
@^(?:(?:[^:/?#]+):)?(?://(?:[^/?#]*))?(?:[^?#]*)(?:\?(?:[^#]*))?(?:#(?:.*))?$@
REGEXP;

    /**
     * @var string A constant/property regular expression
     */
    public const CONSTANT_PROPERTY = <<<'REGEXP'
@^(?:(?<property>\$[a-zA-Z0-9_]+)|(?<constant>[A-Z0-9_]+))$@
REGEXP;

    /**
     * @var string A Fully Qualified Structural Element Name (FQSEN) regular expression
     */
    public const FQSEN = <<<'REGEXP'
@^(?:(?<class>\\?(?:[a-zA-Z0-9_]+\\)*[a-zA-Z0-9_]+)::)?(?:(?<property>\$[a-zA-Z0-9_]+)|(?<method>[a-zA-Z0-9_]+\(\))|(?<constant>[A-Z0-9_]+))$@
REGEXP;
}