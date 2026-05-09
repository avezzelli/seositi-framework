<?php

namespace seositiframework;

/**
 * Interfaccia per il layer View.
 *
 * Definisce il contratto per le classi che gestiscono
 * l'interfaccia utente dei form di inserimento e dettaglio.
 *
 * @package SeoSitiFramework
 * @since   1.0
 */
interface InterfaceView {

    /**
     * Listener per il form dei dettagli: gestisce le azioni
     * di aggiornamento e cancellazione inviate dal form.
     *
     * @return string HTML del messaggio di risultato.
     */
    public function listenerDetailsForm(): string;

    /**
     * Genera l'HTML del form di dettaglio per un record specifico.
     *
     * @param int $ID Identificativo del record.
     * @return string HTML del form.
     */
    public function printDetailsForm(int $ID): string;

    /**
     * Listener per il form di salvataggio: gestisce l'azione
     * di inserimento di un nuovo record.
     *
     * @return string HTML del messaggio di risultato.
     */
    public function listenerSaveForm(): string;

    /**
     * Genera l'HTML del form di inserimento di un nuovo record.
     *
     * @return string HTML del form.
     */
    public function printSaveForm(): string;
}
