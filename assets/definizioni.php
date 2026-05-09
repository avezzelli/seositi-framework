<?php

namespace seositiframework;

/**
 * Definizioni globali del SeoSiti Framework v2.0
 *
 * Tutte le costanti utilizzano il prefisso SSF_ per evitare
 * collisioni con altri plugin o temi.
 *
 * @package SeoSitiFramework
 * @since   2.0
 */

/************************ CONFIGURAZIONE **************************/

/** Numero di elementi per pagina nelle liste paginate */
define('SSF_ELEMENTI_PER_PAGINA', 10);

/** Nome della colonna ID nel database */
define('SSF_DBT_ID', 'ID');

/************************ PREFISSI FORM **************************/

/** Suffisso per il campo ID nei form */
define('SSF_FRM_ID', '-id');

/** Suffisso per il campo nome nei form */
define('SSF_FRM_NOME', '-nome');

/** Suffisso per il campo cognome nei form */
define('SSF_FRM_COGNOME', '-cognome');

/************************ LABEL **************************/

/** Label per il campo Nome */
define('SSF_LBL_NOME', 'Nome');

/** Label per il campo Cognome */
define('SSF_LBL_COGNOME', 'Cognome');

/************************ DEFINIZIONI DI PRINTER-VIEW **************************/

/** Prefisso per il form di aggiunta */
define('SSF_FRM_ADD', 'form-add-');

/** Prefisso per il form dei dettagli */
define('SSF_FRM_DETAILS', 'form-details-');

/** Suffisso per il pulsante salva */
define('SSF_FRM_SAVE', '-save-form');

/** Suffisso per il pulsante aggiorna */
define('SSF_FRM_UPDATE', '-update-form');

/** Suffisso per il pulsante elimina */
define('SSF_FRM_DELETE', '-delete-form');

/** Suffisso per il form di ricerca */
define('SSF_FRM_SEARCH', '-search-form');

/** Nome del pulsante submit della ricerca */
define('SSF_FRM_SEARCH_SUBMIT', 'form-search-submit');

/** Nome del pulsante reset della ricerca */
define('SSF_FRM_SEARCH_RESET', 'form-search-reset');

/************************ LAYOUT BOOTSTRAP **************************/

/** Classe CSS per la colonna etichetta */
define('SSF_COLONNA_ETICHETTA', 'col-sm-3');

/** Classe CSS per la colonna contenuto */
define('SSF_COLONNA_CONTENUTO', 'col-sm-9');


/************************ CLASSI ENUM DI SUPPORTO **************************/

/** FORMATO VARIABILI **/
class Formato{
    public static function NUMERO(){
        return 'INT';
    }
    public static function TESTO(){
        return 'TEXT';
    }
    public static function DATA(){
        return 'TIMESTAMP';
    }   
    public static function OBBLIGATORIO(){
        return 'NOT NULL';
    }
    public static function FACOLTATIVO(){
        return 'NULL';
    }
}

class Campo{    
    public static function NASCOSTO(){
        return 'hidden';
    }
    public static function TESTO(){
        return 'text';
    }
    public static function TELEFONO(){
        return 'tel';
    }
    public static function EMAIL(){
        return 'email';
    }
    public static function PASSWORD(){
        return 'password';
    }
    public static function PREZZO(){
        return 'price';
    }
    public static function AREA_DI_TESTO(){
        return 'textarea';
    }
    public static function IMMAGINE(){
        return 'img';
    }    
    public static function NUMERO(){
        return 'number';
    }
    public static function FILE(){
        return 'file';
    }  
    public static function EDITOR(){
        return 'editor';
    }
    public static function CHECKBOX(){
        return 'checkbox';
    }
    public static function SELECT(){
        return 'select';
    }
    public static function DATA(){
        return 'date';
    }
}

class Target{
    public static function NUOVA_FINESTRA(){
        return '_blank';
    }
}

class Modello{
    public static function FLOAT(){
        return 'float';
    }
    public static function DUE_COLONNE(){
        return 'due-colonne';
    }
}

class Richiesto{
    public static function NO(){
        return '';
    }
    public static function SI(){
        return 'required';
    }
}

class Disabilitato{
    public static function NO(){
        return '';
    }
    public static function SI(){
        return 'disabled';
    }
    public static function SOLA_LETTURA(){
        return 'readonly';
    }
}

class Obbligatorio{
    public static function SI(){
        return true;
    }
    public static function NO(){
        return false;
    }
}

class TypeSelect{
    public static function SINGLE(){
        return 'single';
    }    
    public static function MULTIPLE(){
        return 'multiple';
    }
}
