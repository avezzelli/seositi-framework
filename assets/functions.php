<?php

namespace seositiframework;

/**
 * Funzioni helper globali del SeoSiti Framework.
 *
 * @package SeoSitiFramework
 * @since   2.0
 */

/**
 * Restituisce l'URL corrente della pagina WordPress.
 *
 * Utilizza le funzioni native di WordPress anziché ricostruire
 * manualmente l'URL da $_SERVER (più sicuro e compatibile HTTPS).
 *
 * @return string URL corrente, già escaped per uso in attributi HTML.
 */
function ssf_current_page_url(): string {
    return esc_url(home_url(add_query_arg(null, null)));
}

/**
 * Controlla se un array di risultati contiene dati validi.
 *
 * @param array|null $temp Array di risultati da verificare.
 * @return bool True se l'array è non-null e non-vuoto.
 */
function ssf_check_result(?array $temp): bool {
    return ($temp !== null && count($temp) > 0);
}

/**
 * Restituisce i ruoli dell'utente WordPress corrente.
 *
 * @return array|false Array dei ruoli, o false se l'utente non è loggato.
 */
function ssf_get_role_user(): array|false {
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        return (array) $user->roles;
    }
    return false;
}

/**
 * Restituisce l'email di un utente WordPress dato il suo ID.
 *
 * @param int $id ID dell'utente WordPress.
 * @return string Email dell'utente, o stringa vuota se l'utente non esiste.
 */
function ssf_get_email_by_wp_id(int $id): string {
    $user = get_userdata($id);
    if ($user === false) {
        return '';
    }
    return $user->user_email;
}

/**
 * Costruisce l'array di definizione per un singolo campo di tabella.
 *
 * Utilizzato come helper per passare i parametri a creaTabella().
 *
 * @param string $nome Nome della colonna.
 * @param string $tipo Tipo SQL della colonna (es. 'VARCHAR(255)', 'INT').
 * @param string $null Vincolo NULL (es. 'NOT NULL', 'NULL').
 * @return array Array associativo con le chiavi 'nome', 'tipo', 'null'.
 */
function ssf_get_single_field(string $nome, string $tipo, string $null): array {
    return array(
        'nome' => $nome,
        'tipo' => $tipo,
        'null' => $null,
    );
}

/**
 * Costruisce l'array di definizione per un parametro di query WHERE.
 *
 * @param string $campo   Nome del campo nel database.
 * @param mixed  $valore  Valore da confrontare.
 * @param string $formato Formato del valore ('INT', '%s', '%d', ecc.).
 * @return array Array associativo con le chiavi 'campo', 'valore', 'formato'.
 */
function ssf_get_query_field(string $campo, mixed $valore, string $formato): array {
    return array(
        'campo'   => $campo,
        'valore'  => $valore,
        'formato' => $formato,
    );
}

/**
 * Costruisce l'array di definizione per una foreign key.
 *
 * @param string $id      Nome della colonna FK nella tabella corrente.
 * @param string $tabella Nome della tabella referenziata (senza prefisso).
 * @return array Array associativo con le chiavi 'key1', 'tabella'.
 */
function ssf_get_fks(string $id, string $tabella): array {
    return array(
        'key1'    => $id,
        'tabella' => $tabella,
    );
}

/**
 * Crea una pagina WordPress con il titolo specificato.
 *
 * Verifica che l'utente abbia i permessi e che la pagina non esista già.
 *
 * @param string $titolo Titolo della pagina da creare.
 * @return int ID del post creato, o 0 se già esistente o non autorizzato.
 */
function ssf_create_page(string $titolo): int {
    if (! current_user_can('publish_pages')) {
        return 0;
    }

    $args = array(
        'post_title'   => sanitize_text_field($titolo),
        'post_content' => '',
        'post_type'    => 'page',
        'post_status'  => 'publish',
    );

    if (post_exists($titolo) === 0) {
        return wp_insert_post($args);
    }
    return 0;
}