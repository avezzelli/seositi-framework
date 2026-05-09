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