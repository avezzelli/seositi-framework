<?php

namespace seositiframework;

/**
 * Classe base della view che identifica tutti i metodi di rendering HTML.
 *
 * Questa classe utilizza Bootstrap 5.3 e ritorna sempre stringhe HTML,
 * senza mai stampare direttamente a video (no echo).
 * Include controlli CSRF (Nonce) e escaping XSS.
 *
 * @package SeoSitiFramework
 * @since   2.0
 */
class PrinterView {

    private array $mesi;
    private array $giorni;
    private int $annoCorrente;
    private string $meseCorrente;
    private int $giornoCorrente;
    private string $settimanaCorrente;
    private array $countries;
    private array $province;
    private array $operatore;
    
    public function __construct() {
        $this->operatore = array('<=' => '<=', '>=' => '>=');        
        $this->mesi = array('01' => 'Gennaio', '02' => 'Febbraio', '03' => 'Marzo', '04' => 'Aprile', '05' => 'Maggio', '06' => 'Giugno', '07' => 'Luglio', '08' => 'Agosto', '09' => 'Settembre', '10' => 'Ottobre', '11' => 'Novembre', '12' => 'Dicembre');        
        $this->giorni = array('1'  => 'Lunedì', '2'  => 'Martedì', '3'  => 'Mercoledì', '4'  => 'Giovedì', '5'  => 'Venerdì', '6'  => 'Sabato', '7'  => 'Domenica');        
        $this->countries = array('AF'=>'Afghanistan','AX'=>'Aland Islands','AL'=>'Albania','DZ'=>'Algeria','AS'=>'American Samoa','AD'=>'Andorra','AO'=>'Angola','AI'=>'Anguilla','AQ'=>'Antarctica','AG'=>'Antigua And Barbuda','AR'=>'Argentina','AM'=>'Armenia','AW'=>'Aruba','AU'=>'Australia','AT'=>'Austria','AZ'=>'Azerbaijan','BS'=>'Bahamas','BH'=>'Bahrain','BD'=>'Bangladesh','BB'=>'Barbados','BY'=>'Belarus','BE'=>'Belgium','BZ'=>'Belize','BJ'=>'Benin','BM'=>'Bermuda','BT'=>'Bhutan','BO'=>'Bolivia','BA'=>'Bosnia And Herzegovina','BW'=>'Botswana','BV'=>'Bouvet Island','BR'=>'Brazil','IO'=>'British Indian Ocean Territory','BN'=>'Brunei Darussalam','BG'=>'Bulgaria','BF'=>'Burkina Faso','BI'=>'Burundi','KH'=>'Cambodia','CM'=>'Cameroon','CA'=>'Canada','CV'=>'Cape Verde','KY'=>'Cayman Islands','CF'=>'Central African Republic','TD'=>'Chad','CL'=>'Chile','CN'=>'China','CX'=>'Christmas Island','CC'=>'Cocos (Keeling) Islands','CO'=>'Colombia','KM'=>'Comoros','CG'=>'Congo','CD'=>'Congo, Democratic Republic','CK'=>'Cook Islands','CR'=>'Costa Rica','CI'=>'Cote D\'Ivoire','HR'=>'Croatia','CU'=>'Cuba','CY'=>'Cyprus','CZ'=>'Czech Republic','DK'=>'Denmark','DJ'=>'Djibouti','DM'=>'Dominica','DO'=>'Dominican Republic','EC'=>'Ecuador','EG'=>'Egypt','SV'=>'El Salvador','GQ'=>'Equatorial Guinea','ER'=>'Eritrea','EE'=>'Estonia','ET'=>'Ethiopia','FK'=>'Falkland Islands (Malvinas)','FO'=>'Faroe Islands','FJ'=>'Fiji','FI'=>'Finland','FR'=>'France','GF'=>'French Guiana','PF'=>'French Polynesia','TF'=>'French Southern Territories','GA'=>'Gabon','GM'=>'Gambia','GE'=>'Georgia','DE'=>'Germany','GH'=>'Ghana','GI'=>'Gibraltar','GR'=>'Greece','GL'=>'Greenland','GD'=>'Grenada','GP'=>'Guadeloupe','GU'=>'Guam','GT'=>'Guatemala','GG'=>'Guernsey','GN'=>'Guinea','GW'=>'Guinea-Bissau','GY'=>'Guyana','HT'=>'Haiti','HM'=>'Heard Island & Mcdonald Islands','VA'=>'Holy See (Vatican City State)','HN'=>'Honduras','HK'=>'Hong Kong','HU'=>'Hungary','IS'=>'Iceland','IN'=>'India','ID'=>'Indonesia','IR'=>'Iran, Islamic Republic Of','IQ'=>'Iraq','IE'=>'Ireland','IM'=>'Isle Of Man','IL'=>'Israel','IT'=>'Italy','JM'=>'Jamaica','JP'=>'Japan','JE'=>'Jersey','JO'=>'Jordan','KZ'=>'Kazakhstan','KE'=>'Kenya','KI'=>'Kiribati','KR'=>'Korea','KW'=>'Kuwait','KG'=>'Kyrgyzstan','LA'=>'Lao People\'s Democratic Republic','LV'=>'Latvia','LB'=>'Lebanon','LS'=>'Lesotho','LR'=>'Liberia','LY'=>'Libyan Arab Jamahiriya','LI'=>'Liechtenstein','LT'=>'Lithuania','LU'=>'Luxembourg','MO'=>'Macao','MK'=>'Macedonia','MG'=>'Madagascar','MW'=>'Malawi','MY'=>'Malaysia','MV'=>'Maldives','ML'=>'Mali','MT'=>'Malta','MH'=>'Marshall Islands','MQ'=>'Martinique','MR'=>'Mauritania','MU'=>'Mauritius','YT'=>'Mayotte','MX'=>'Mexico','FM'=>'Micronesia, Federated States Of','MD'=>'Moldova','MC'=>'Monaco','MN'=>'Mongolia','ME'=>'Montenegro','MS'=>'Montserrat','MA'=>'Morocco','MZ'=>'Mozambique','MM'=>'Myanmar','NA'=>'Namibia','NR'=>'Nauru','NP'=>'Nepal','NL'=>'Netherlands','AN'=>'Netherlands Antilles','NC'=>'New Caledonia','NZ'=>'New Zealand','NI'=>'Nicaragua','NE'=>'Niger','NG'=>'Nigeria','NU'=>'Niue','NF'=>'Norfolk Island','MP'=>'Northern Mariana Islands','NO'=>'Norway','OM'=>'Oman','PK'=>'Pakistan','PW'=>'Palau','PS'=>'Palestinian Territory, Occupied','PA'=>'Panama','PG'=>'Papua New Guinea','PY'=>'Paraguay','PE'=>'Peru','PH'=>'Philippines','PN'=>'Pitcairn','PL'=>'Poland','PT'=>'Portugal','PR'=>'Puerto Rico','QA'=>'Qatar','RE'=>'Reunion','RO'=>'Romania','RU'=>'Russian Federation','RW'=>'Rwanda','BL'=>'Saint Barthelemy','SH'=>'Saint Helena','KN'=>'Saint Kitts And Nevis','LC'=>'Saint Lucia','MF'=>'Saint Martin','PM'=>'Saint Pierre And Miquelon','VC'=>'Saint Vincent And Grenadines','WS'=>'Samoa','SM'=>'San Marino','ST'=>'Sao Tome And Principe','SA'=>'Saudi Arabia','SN'=>'Senegal','RS'=>'Serbia','SC'=>'Seychelles','SL'=>'Sierra Leone','SG'=>'Singapore','SK'=>'Slovakia','SI'=>'Slovenia','SB'=>'Solomon Islands','SO'=>'Somalia','ZA'=>'South Africa','GS'=>'South Georgia And Sandwich Isl.','ES'=>'Spain','LK'=>'Sri Lanka','SD'=>'Sudan','SR'=>'Suriname','SJ'=>'Svalbard And Jan Mayen','SZ'=>'Swaziland','SE'=>'Sweden','CH'=>'Switzerland','SY'=>'Syrian Arab Republic','TW'=>'Taiwan','TJ'=>'Tajikistan','TZ'=>'Tanzania','TH'=>'Thailand','TL'=>'Timor-Leste','TG'=>'Togo','TK'=>'Tokelau','TO'=>'Tonga','TT'=>'Trinidad And Tobago','TN'=>'Tunisia','TR'=>'Turkey','TM'=>'Turkmenistan','TC'=>'Turks And Caicos Islands','TV'=>'Tuvalu','UG'=>'Uganda','UA'=>'Ukraine','AE'=>'United Arab Emirates','GB'=>'United Kingdom','US'=>'United States','UM'=>'United States Outlying Islands','UY'=>'Uruguay','UZ'=>'Uzbekistan','VU'=>'Vanuatu','VE'=>'Venezuela','VN'=>'Viet Nam','VG'=>'Virgin Islands, British','VI'=>'Virgin Islands, U.S.','WF'=>'Wallis And Futuna','EH'=>'Western Sahara','YE'=>'Yemen','ZM'=>'Zambia','ZW'=>'Zimbabwe');
        $this->province = array('AG'=>'Agrigento','AL'=>'Alessandria','AN'=>'Ancona','AO'=>'Aosta','AR'=>'Arezzo','AP'=>'Ascoli Piceno','AT'=>'Asti','AV'=>'Avellino','BA'=>'Bari','BT'=>'Barletta-Andria-Trani','BL'=>'Belluno','BN'=>'Benevento','BG'=>'Bergamo','BI'=>'Biella','BO'=>'Bologna','BZ'=>'Bolzano','BS'=>'Brescia','BR'=>'Brindisi','CA'=>'Cagliari','CL'=>'Caltanissetta','CB'=>'Campobasso','CI'=>'Carbonia-Iglesias','CE'=>'Caserta','CT'=>'Catania','CZ'=>'Catanzaro','CH'=>'Chieti','CO'=>'Como','CS'=>'Cosenza','CR'=>'Cremona','KR'=>'Crotone','CN'=>'Cuneo','EN'=>'Enna','FM'=>'Fermo','FE'=>'Ferrara','FI'=>'Firenze','FG'=>'Foggia','FC'=>'Forlì-Cesena','FR'=>'Frosinone','GE'=>'Genova','GO'=>'Gorizia','GR'=>'Grosseto','IM'=>'Imperia','IS'=>'Isernia','SP'=>'La Spezia','AQ'=>'L\'Aquila','LT'=>'Latina','LE'=>'Lecce','LC'=>'Lecco','LI'=>'Livorno','LO'=>'Lodi','LU'=>'Lucca','MC'=>'Macerata','MN'=>'Mantova','MS'=>'Massa-Carrara','MT'=>'Matera','ME'=>'Messina','MI'=>'Milano','MO'=>'Modena','MB'=>'Monza e della Brianza','NA'=>'Napoli','NO'=>'Novara','NU'=>'Nuoro','OT'=>'Olbia-Tempio','OR'=>'Oristano','PD'=>'Padova','PA'=>'Palermo','PR'=>'Parma','PV'=>'Pavia','PG'=>'Perugia','PU'=>'Pesaro e Urbino','PE'=>'Pescara','PC'=>'Piacenza','PI'=>'Pisa','PT'=>'Pistoia','PN'=>'Pordenone','PZ'=>'Potenza','PO'=>'Prato','RG'=>'Ragusa','RA'=>'Ravenna','RC'=>'Reggio Calabria','RE'=>'Reggio Emilia','RI'=>'Rieti','RN'=>'Rimini','RM'=>'Roma','RO'=>'Rovigo','SA'=>'Salerno','VS'=>'Medio Campidano','SS'=>'Sassari','SV'=>'Savona','SI'=>'Siena','SR'=>'Siracusa','SO'=>'Sondrio','TA'=>'Taranto','TE'=>'Teramo','TR'=>'Terni','TO'=>'Torino','OG'=>'Ogliastra','TP'=>'Trapani','TN'=>'Trento','TV'=>'Treviso','TS'=>'Trieste','UD'=>'Udine','VA'=>'Varese','VE'=>'Venezia','VB'=>'Verbano-Cusio-Ossola','VC'=>'Vercelli','VR'=>'Verona','VV'=>'Vibo Valentia','VI'=>'Vicenza','VT'=>'Viterbo');
              
        date_default_timezone_set('Europe/Rome');
        $this->annoCorrente = intval(date('Y'));
        $this->meseCorrente = date('m');
        $this->giornoCorrente = intval(date('d'));
                
        try {
            $date = new \DateTime($this->annoCorrente.'-'.$this->meseCorrente.'-'.$this->giornoCorrente);
            $this->settimanaCorrente = $date->format("W");
        } catch (\Exception $e) {
            $this->settimanaCorrente = '01';
        }
    }
    
    /**
     * Aggiunge un container al dom
     * @param string $content
     * @param string $typeContainer
     * @return string
     */
    protected function addContainer(string $content, string $typeContainer = ''): string {
        $classFluid = '';
        if ($typeContainer !== '') {
            $classFluid = '-' . esc_attr($typeContainer);
        }
        $html = '<div class="container' . $classFluid . '">';
        $html .= $content;
        $html .= '</div>';
        return $html;
    } 

    /**
     * Ritorna l'HTML per un form di salvataggio (aggiunta)
     * @param string $name
     * @param string $content
     * @param bool $files
     * @return string
     */
    protected function saveForm(string $name, string $content, bool $files = false): string {
        $add = $files ? 'enctype="multipart/form-data"' : '';
        
        $html = '<form role="form" action="' . ssf_current_page_url() . '" method="POST" name="' . esc_attr(SSF_FRM_ADD . $name) . '" ' . $add . ' class="g-3 form-floating needs-validation">';
        $html .= wp_nonce_field('save_form_' . $name, 'ssf_nonce', true, false);
        $html .= $content; 
        $html .= $this->submitButton($name . SSF_FRM_SAVE, 'SALVA');
        $html .= '</form>';
        return $html;
    }
    
    /**
     * Ritorna l'HTML per un form di dettaglio (modifica/elimina)
     * @param string $name
     * @param string $content
     * @param bool $files
     * @return string
     */
    protected function detailForm(string $name, string $content, bool $files = false): string {
        $add = $files ? 'enctype="multipart/form-data"' : '';
            
        $html = '<form role="form" action="' . ssf_current_page_url() . '" method="POST" name="' . esc_attr(SSF_FRM_DETAILS . $name) . '" ' . $add . ' class="g-3 form-floating needs-validation">';
        $html .= wp_nonce_field('detail_form_' . $name, 'ssf_nonce', true, false);
        $html .= $content; 
        
        $html .= '<div class="col-12 mt-4">';
        $html .= $this->getButton($name . SSF_FRM_UPDATE, 'AGGIORNA');
        $html .= '&nbsp;';
        $html .= $this->getButton($name . SSF_FRM_DELETE, 'ELIMINA', 'btn-danger');        
        $html .= '</div>';
        $html .= '</form>';
        
        return $html;
    }
    
    protected function getButton(string $nameField, string $value, string $btnClass = 'btn-primary'): string {
        return '<button class="btn ' . esc_attr($btnClass) . '" type="submit" name="' . esc_attr($nameField) . '">' . esc_html($value) . '</button>';
    }
    
    protected function submitButton(string $nameField, string $value): string {
        $html = '<div class="col-xs-12 col-sm-3 mt-4">';
        $html .= '<button class="btn btn-primary" type="submit" name="' . esc_attr($nameField) . '">' . esc_html($value) . '</button>';
        $html .= '</div>';
        return $html;
    }
   
    protected function linkButton(string $testo, string $link): string {
        return '<a class="btn btn-outline-primary" href="' . esc_url($link) . '" role="button">' . esc_html($testo) . '</a>';
    }
      
    /**
     * Restituisce la stampa di un oggetto html input
     */
    protected function printInput(string $modello, string $type, string $name, string $label, string $required = '', ?string $value = null, string $disabled = ''): string {
        if ($value === null && isset($_POST[$name])) {
            $value = sanitize_text_field(wp_unslash($_POST[$name]));
        }
        
        if ($type === 'hidden') {
            return '<input class="input-hidden" type="hidden" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '" />';
        }
        
        if ($modello === 'float') {
            return $this->getFloatLayout($type, $name, $label, $required, $value, $disabled);
        } elseif ($modello === 'due-colonne') {
            return $this->get2ColonneLayout($type, $name, $label, $required, $value, $disabled);
        }
       
        return '';
    }
    
    private function getFloatLayout(string $type, string $name, string $label, string $required = '', ?string $value = null, string $disabled = ''): string {
        $input = '';
        $classDiv1 = 'form-floating mb-3';
        
        $attrName = esc_attr($name);
        $attrLabel = esc_html($label);
        $attrValue = esc_attr((string)$value);
        $attrReq = esc_attr($required);
        $attrDis = esc_attr($disabled);

        if ($type === 'price') {
            $classDiv1 = 'input-group mb-3';
            $input .= '<div class="form-floating">'
                    . '<input type="number" min="0" step="0.01" class="form-control" name="' . $attrName . '" id="' . $attrName . '" value="' . $attrValue . '" placeholder="' . $attrValue . '" ' . $attrReq . ' ' . $attrDis . '>'
                    . '<label for="' . $attrName . '">' . $attrLabel . '</label>'
                    . '</div>'
                    . '<span class="input-group-text">€</span>';
        } elseif (in_array($type, ['text', 'email', 'tel', 'file'], true)) {
            $input .= '<input type="' . esc_attr($type) . '" class="form-control" name="' . $attrName . '" id="' . $attrName . '" value="' . $attrValue . '" placeholder="' . $attrValue . '" ' . $attrReq . ' ' . $attrDis . '>'
                    . '<label for="' . $attrName . '">' . $attrLabel . '</label>';
        } elseif ($type === 'textarea') {
            $input .= '<textarea rows="4" style="height:100%;" class="form-control" name="' . $attrName . '" id="' . $attrName . '" ' . $attrReq . ' ' . $attrDis . '>' . esc_textarea((string)$value) . '</textarea>'
                    . '<label for="' . $attrName . '">' . $attrLabel . '</label>';
        }
        
        return '<div class="' . esc_attr($classDiv1) . '">' . $input . '</div>';
    }
    
    private function get2ColonneLayout(string $type, string $name, string $label, string $required = '', ?string $value = null, string $disabled = ''): string {
        $attrName = esc_attr($name);
        $attrLabel = esc_html($label);
        $attrValue = esc_attr((string)$value);
        $attrReq = esc_attr($required);
        $attrDis = esc_attr($disabled);

        $html = '<div class="row mb-3">';
        $html .= '<label for="' . $attrName . '" class="col-sm-3 col-form-label form-label">' . $attrLabel . '</label>';
        $html .= '<div class="col-sm-9">';
        
        if ($type === 'price') {
            $html .= '<div class="input-group">';
            $html .= '<input type="number" min="0" step="0.01" class="form-control" name="' . $attrName . '" value="' . $attrValue . '" id="' . $attrName . '" ' . $attrReq . ' ' . $attrDis . '>';
            $html .= '<span class="input-group-text" id="basic-addon1">€</span>';
            $html .= '</div>';
        } elseif (in_array($type, ['text', 'email', 'tel', 'file'], true)) {
            $html .= '<input type="' . esc_attr($type) . '" class="form-control" name="' . $attrName . '" value="' . $attrValue . '" id="' . $attrName . '" ' . $attrReq . ' ' . $attrDis . '>';
        } elseif ($type === 'textarea') {
            $html .= '<textarea rows="4" style="height:100%;" class="form-control" name="' . $attrName . '" id="' . $attrName . '" ' . $attrReq . ' ' . $attrDis . '>' . esc_textarea((string)$value) . '</textarea>';
        }
        $html .= '</div></div>';
        
        return $html;
    }
   
    protected function printSelect(string $modello, string $type, string $name, string $label, array $array, string $required = '', $value = null, string $disabled = ''): string {
        if ($value === null && isset($_POST[$name])) {
            if (is_array($_POST[$name])) {
                $value = array_map('sanitize_text_field', wp_unslash($_POST[$name]));
            } else {
                $value = sanitize_text_field(wp_unslash($_POST[$name]));
            }
        }
        
        $html = '';
        if ($modello === 'float') {
            $html .= '<div class="form-floating mb-3">';            
            $html .= $this->getSelect($type, $name, $label, $array, $required, $disabled, $value);            
            $html .= '<label for="' . esc_attr($name) . '">' . esc_html($label) . '</label>';            
            $html .= '</div>';
        } elseif ($modello === 'due-colonne') {
            $html .= '<div class="row mb-3">';
            $html .= '<label for="' . esc_attr($name) . '" class="col-sm-3 col-form-label form-label">' . esc_html($label) . '</label>';
            $html .= '<div class="col-sm-9">'; 
            $html .= $this->getSelect($type, $name, $label, $array, $required, $disabled, $value);            
            $html .= '</div></div>';
        }                
        return $html;
    }
    
    private function getSelect(string $type, string $name, string $label, array $array, string $required, string $disabled, $value): string {
        $html = '';
        $attrName = esc_attr($name);
        
        $script = '<script type="text/javascript">';
        $script .= ' jQuery(document).ready(function() {';
        $script .= '  jQuery(".multiselect-' . $attrName . '").multipleSelect({ filter: true });';
        $script .= ' });';
        $script .= '</script>';
        
        $class = 'class="form-select"';
        
        if ($type === 'multiple') {
            $class = ' class="multiselect multiselect-' . $attrName . '" multiple="multiple" size="' . count($array) . '" ';
            $attrName .= '[]';
        } else {
            $script = ''; // Non serve multipleSelect
        }
        
        if (empty($disabled)) {
            $html .= '<select ' . $class . ' id="' . esc_attr($name) . '" name="' . $attrName . '" aria-label="' . esc_attr($label) . '" ' . esc_attr($required) . '>';
            $html .= '<option value=""></option>';
            foreach ($array as $k => $v) {
                $isSelected = false;
                if (is_array($value) && in_array($k, $value)) {
                    $isSelected = true;
                } elseif ($value !== null && $value == $k) {
                    $isSelected = true;
                }
                $selectedAttr = $isSelected ? 'selected="selected"' : '';
                $html .= '<option value="' . esc_attr($k) . '" ' . $selectedAttr . '>' . esc_html($v) . '</option>';
            } 
            $html .= '</select>';
            $html .= $script;    
        } else {
            $selectedText = '';
            if (is_array($value)) {
                $selectedItems = array();
                foreach ($value as $item) {
                    if (isset($array[$item])) {
                        $selectedItems[] = $array[$item];
                    }
                }
                $selectedText = implode(', ', $selectedItems);
            } elseif ($value !== null && isset($array[$value])) {
                $selectedText = $array[$value];
            }
            $html .= '<input type="text" class="form-control" disabled value="' . esc_attr($selectedText) . '">';
        }           
        return $html;
    }

    protected function printTable(string $name, array $header, array $rows): string {
        $html = '<div class="table-responsive-sm">';
        $html .= '<table id="sorting-table-' . esc_attr($name) . '" class="table table-hover table-striped table-bordered">';
        
        $html .= '<thead><tr>';        
        foreach ($header as $th) {
            $html .= '<th class="th-sm">' . esc_html($th) . '</th>';
        }        
        $html .= '</tr></thead>';
        
        $html .= '<tbody>';        
        $html .= $this->printRows($rows);        
        $html .= '</tbody></table></div>';
        
        return $html;
    }
    
    private function printRows(array $rows): string {
        $html = '';
        foreach ($rows as $columns) {
            $html .= '<tr>';
            foreach ($columns as $column) {
                // Qui assumiamo che $column possa contenere HTML (es. bottoni azioni),
                // quindi lo sviluppatore del child theme DEVE fare escaping dei dati prima.
                $html .= '<td>' . $column . '</td>';
            }            
            $html .= '</tr>';
        }
        return $html;
    }

    // ==========================================
    // UTILITIES E MESSAGGI (RITORNANO STRINGHE)
    // ==========================================

    protected function errorBox(string $message): string {
        return '<div class="alert alert-danger"><strong>Errore!</strong> ' . esc_html($message) . '</div>';
    }

    public static function printErrorBoxMessage(string $message): string {
        return '<div class="alert alert-danger" role="alert"><strong>Errore!</strong> ' . esc_html($message) . '</div>';
    }
    
    protected function printWarningBoxMessage(string $message): string {
        return '<div class="alert alert-warning" role="alert"><strong>Attenzione!</strong> ' . esc_html($message) . '</div>';        
    }
    
    protected function printOkBoxMessage(string $message): string {
        return '<div class="alert alert-success" role="alert"><strong>OK!</strong> ' . esc_html($message) . '</div>';
    }

    protected function printMessaggeAfterSave(string $type, $save): string {
        if ($save === -1) {
            return $this->printErrorBoxMessage($type . ' già presente nel sistema.');            
        } elseif ($save === false) {
            return $this->printErrorBoxMessage('Errore nel salvataggio!');
        } elseif ($save > 0) {
            return $this->printOkBoxMessage('Salvataggio avvenuto con successo!');            
        }        
        return '';        
    }
    
    protected function printMessageAfterUpdate(bool $update): string {
        if ($update === true) {
            return $this->printOkBoxMessage('Aggiornamento avvenuto con successo!');                      
        } else {
            return $this->printWarningBoxMessage('Aggiornamento non necessario o fallito.');            
        }
    }
    
    protected function printMessageAfterDelete(bool $delete): string {
        if ($delete === true) {
            return $this->printOkBoxMessage('Eliminazione avvenuta con successo!');                      
        } else {
            return $this->printErrorBoxMessage('Errore nella cancellazione');            
        }
    }

    protected function printNonAutorizzato(): string {
        return '<p class="error text-danger">Non sei autorizzato a vedere questa pagina.</p>';
    }

    // ==========================================
    // VALIDATORI DEI CAMPI REQUEST
    // ==========================================

    protected function check(string $type, string $name, string $label, bool $required = false) {
        $result = false;
        switch ($type) {
            case 'text':
            case 'select':
            case 'email':
            case 'tel':
            case 'textarea':
            case 'price':
                if ($required) {
                    $result = $this->checkRequiredSingleField($name, $label);
                } else {
                    $result = $this->checkSingleField($name);
                }
                break;
            case 'file':
                $result = $this->checkUploadFileField($name);
                break;
            case 'multiple-select':
                $result = $this->checkMultipleSelectField($name);                 
                break;
            case 'date':
                $result = $this->checkDateField($name);
                break;
        }
        return $result;       
    }

    protected function checkRequiredSingleField(string $nameField, string $labelField) {
        if (isset($_POST[$nameField]) && trim($_POST[$nameField]) !== '') {
            return sanitize_text_field(wp_unslash(trim($_POST[$nameField])));
        }
        // Qui ritorna false e l'handler sopra (se lo gestisce) stamperà l'errore
        return false;        
    }
    
    protected function checkSingleField(string $nameField) {
        if (isset($_POST[$nameField]) && trim($_POST[$nameField]) !== '') {
            return sanitize_text_field(wp_unslash(trim($_POST[$nameField])));
        }
        return false;
    }
    
    protected function checkUploadFileField(string $nameField) {       
        if (isset($_FILES[$nameField]) && $_FILES[$nameField]['error'] === 0) {
            // Assicurati di includere i file necessari per media_handle_sideload o wp_upload_bits
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            
            $file = $_FILES[$nameField];
            $upload = wp_upload_bits($file['name'], null, file_get_contents($file['tmp_name']));
            return $upload;
        }
        return null;
    }
    
    protected function checkMultipleSelectField(string $nameField) {  
        if (isset($_POST[$nameField]) && is_array($_POST[$nameField]) && count($_POST[$nameField]) > 0) {
            return array_map('sanitize_text_field', wp_unslash($_POST[$nameField]));
        }
        return false;
    }
    
    protected function checkDateField(string $nameField) {
        if (isset($_POST[$nameField . '-d'], $_POST[$nameField . '-m'], $_POST[$nameField . '-y'])) {
            $d = intval($_POST[$nameField . '-d']);
            $m = intval($_POST[$nameField . '-m']);
            $y = intval($_POST[$nameField . '-y']);
            
            // Format yyyy-mm-dd
            return sprintf('%04d-%02d-%02d', $y, $m, $d);
        }
        return false;
    }
    
    // ==========================================
    // METODI DI UTILITA'
    // ==========================================

    public function translateDate(string $date): string {       
        $temp = explode('-', $date);
        if (count($temp) === 3) {
            // Nota: questo assume un certo formato, potrebbe rompersi se la data è invalida.
            // Sarebbe meglio usare date_i18n() in WordPress.
            $giorno_idx = date('N', strtotime($date));
            $giorno = $this->giorni[$giorno_idx] ?? '';
            $mese = $this->mesi[$temp[1]] ?? '';
            return $giorno . ' ' . $temp[2] . ' ' . $mese;
        }
        return $date;
    }
    
    public function translateBirthDayDate(string $date): string {
        $temp = explode('-', $date);
        if (count($temp) === 3) {
            return $temp[2] . ' - ' . $temp[1] . ' - ' . $temp[0];
        }
        return $date;
    }
    
    public function getCountries(): array {
        return $this->countries;
    }
    
    protected function getProvince(): array {
        return $this->province;
    }
    
    protected function getOperatori(): array {
        return $this->operatore;
    }
}
