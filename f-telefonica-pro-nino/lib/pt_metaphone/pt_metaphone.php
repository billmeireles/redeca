<?php
/*
 *	pt_metaphone() "Portuguese Metaphone"
 *	version 1.0
 *
 *	Essa fun��o pega uma palavra em portugu�s do Brasil e a retorna
 *	em uma chave metaf�nica.
 *
 *	Copyright (C) 2008		Prefeitura Municipal de V�rzea Paulista
 *							<metaphone@varzeapaulista.sp.gov.br>
 *
 *	Hist�rico:
 *	2008-05-20		Vers�o 1.0
 *					Initial Release
 *
 *	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 *
 *	RECONHECIMENTO :
 *
 *	Essa fun��o foi adaptada de uma fun��o  chamada spanish_metaphone
 *	do Israel J. Sustaita. O c�digo fonte original pode ser obtido em
 *	http://www.geocities.com/isloera/spanish_methaphone.txt (baseada
 *	na vers�o original do DoubleMetaphone em ingl�s de Geoff Caplan).
 *
 *	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 *
 *	AGRADECIMENTOS :
 *	S�lvia e Thaiza pela ajuda com a l�ngua portuguesa
 *
 *	EQUIPE DE DESENVOLVIMENTO :
 * 		Rodrigo Domingos Pinto Lotierzo
 *		Giovanni dos Reis Nunes
 *
 * 		Estagi�rios:
 * 		  Caio Varlei Righi Schleich
 *		  Diego Jorge de Souza
 *		  Sueli Silvestre da Silva
 *
 *	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 *
 *	Funcionamento:
 *
 *	1.	Esta fun��o recebe a string contendo o nome, palavra ou frase
 *		a ser criada a chave e retorna essa chave.
 *
 *	2.	Receber a 'string', a primeira coisa a ser feita � substituir
 *		os d�grafos e encontros consonantais pelas letras que corres-
 *		pondem aos seus sons.
 *
 *	3.	Os d�grafos "LH", "NH" e o encontro consonantal "RR" s�o con-
 *		vertidos em n�meros para facilitar a interpreta��o.
 *
 *	4.	Os d�grafos "CH" e "PH" (quest�es hist�ricas), o encontro con-
 *		sonantal "SC" e o "�" s�o convertidos em seus fonemas corres-
 *		pondentes.
 *
 *	5.	Os acentos s�o removidos das vogais.
 *
 *	6.	Letras cujos fonemas n�o se alteram n�o s�o mexidas ("B", "V",
 *		"P",etc...).
 *
 * 	7.	Outras letras como "G" e "X" s�o tratadas de acordo com casos
 *		espec�ficos.
 *
 *	Hist�rico:
 *	2011-09-05		Vers�o 1.1
 *
 *	EQUIPE DE DESENVOLVIMENTO :
 * 		Fabricio Meireles Monteiro   -   S2IT Solutions
 *
 *
 *
 *	Ajustes:
 *
 *	1.	Foram realizados ajustes para adequar a nova vers�o disponibilizada.
 * *	Hist�rico:
 *	2011-01-18		Vers�o 1.2
 *
 *	EQUIPE DE DESENVOLVIMENTO :
 * 		Thiago Henriques Alves Correa   -   S2IT Solutions
 *
 *
 *
 *	Ajustes:
 *
 *	1.	Novos ajustes para adequar a nova vers�o disponibilizada.
 */

function portuguese_metaphone($STRING,$LENGTH=50)
{
    /*
     *    inicializa a chave metaf�nica
     */
    $META_KEY = "";

    /*
     *    configura o tamanho m�ximo da chave metaf�nica
     */
    $KEY_LENGTH = (int)$LENGTH;


    /*
     *    coloca a posi��o no come�o
     */
    $CURRENT_POS = (int)0;

    /*
     *    recupera o tamanho m�ximo da string
     */
    $STRING_LENGTH = (int)strlen($STRING);

    /*
     *    configura o final da string
     */
    $END_OF_STRING_POS=$STRING_LENGTH-1;
    $ORIGINAL_STRING=$STRING."    ";

    /*
     *    vamos repor alguns caracteres portugueses facilmente confundidos, 
     *    substituindo-os por n�meros. N�o confundir com os 
     *    encontros consonantais (RR), 
     *    d�grafos (LH, NH) e o
     *    C-cedilha:
     *
     *    'LH' to '1'
     *    'RR' to '2'
     *    'NH' to '3'
     *    '�'  to 'SS'
     *    'CH' to 'X'
     */
    $ORIGINAL_STRING = str_replace('[1|2|3|4|5|6|7|8|9|0]',' ',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('[�|�|�]','A',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('[�|�]','E',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('[�|y]','I',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('[�|�|�]','O',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('[�|�]','U',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('�','S',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('�','S',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('y','I',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('Y','I',$ORIGINAL_STRING);

	$ORIGINAL_STRING = str_replace('HH','H',$ORIGINAL_STRING);	
	$ORIGINAL_STRING = str_replace('DD','D',$ORIGINAL_STRING);
	$ORIGINAL_STRING = str_replace('PP','P',$ORIGINAL_STRING);
	$ORIGINAL_STRING = str_replace('LL','L',$ORIGINAL_STRING);
	$ORIGINAL_STRING = str_replace('BB','B',$ORIGINAL_STRING);
	$ORIGINAL_STRING = str_replace('TT','T',$ORIGINAL_STRING);
	$ORIGINAL_STRING = str_replace('MM','M',$ORIGINAL_STRING);
	$ORIGINAL_STRING = str_replace('FF','F',$ORIGINAL_STRING);
	$ORIGINAL_STRING = str_replace('NN','N',$ORIGINAL_STRING);
	$ORIGINAL_STRING = str_replace('QQ','Q',$ORIGINAL_STRING);
	$ORIGINAL_STRING = str_replace('EE','E',$ORIGINAL_STRING);
	$ORIGINAL_STRING = str_replace('OO','O',$ORIGINAL_STRING);
    
    /*
     *    Converte a string para caixa alta
     */
    $ORIGINAL_STRING = strtoupper($ORIGINAL_STRING);

    /*
     * palavras que encaixam neste trecho: "olho", "ninho", "carro"
     */
    $ORIGINAL_STRING = str_replace('LH','1',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('NH','3',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('RR','2',$ORIGINAL_STRING);
    
    /*
     *    a corre��o do TH e do PH por conta dos nomes pr�prios:
     *    -> "schiffer", "theodora", "ophelia"
     */
    $ORIGINAL_STRING = str_replace('TH','T',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('PH','F',$ORIGINAL_STRING);

    /*
     *    remove espa�os extras
     */
    $ORIGINAL_STRING = trim($ORIGINAL_STRING);
	
	$LAST_CHAR = "";

    /*
     *    loop principal
     */
    while ( strlen($META_KEY) < $KEY_LENGTH )
    {
        /*
         *    sai do loop se maior que o tamanho da string
         */
        if ($CURRENT_POS >= $STRING_LENGTH)
        {
            break;
        }
        /*
         *    pega um caracter da string
         */
        $CURRENT_CHAR = substr($ORIGINAL_STRING, $CURRENT_POS, 1);

        /*
         *    se � uma vogal e faz parte do come�o da string,
         *    coloque-a como parte da metachave
         */
               
        if( (is_vowel($ORIGINAL_STRING, $CURRENT_POS)) && ( ($CURRENT_POS == 0) || (string_at($ORIGINAL_STRING, $CURRENT_POS-1, 1," "))) )
        {
            $META_KEY .= $CURRENT_CHAR;
            $CURRENT_POS += 1;
        }
        /*
         * procurar por consoantes que tem um �nico som, ou que que j� foram substitu�das ou soam parecido, como '�' para 'S' e 'NH' para '3'
         */
        elseif( string_at($ORIGINAL_STRING, $CURRENT_POS, 1, array('1','2','3','B','D','F','J','K','M','T','V')) )
        {
            $META_KEY .= $CURRENT_CHAR;

            /*
             *    incrementar por 2 se uma letra repetida for encontrada
             */
            if ( substr($ORIGINAL_STRING, $CURRENT_POS + 1,1) == $CURRENT_CHAR )
            {				
                $CURRENT_POS += 2;
            }

            /*
             *    sen�o incrementa em 1
             */
            $CURRENT_POS += 1;
        }
        else
        {
            /*
             *    checar consoantes com som confuso e similar
             */
				switch ( $CURRENT_CHAR )
				{
				
				case 'L':
                   if( substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1) == 'H' )
                    {
						$META_KEY   .= '1';
                        $CURRENT_POS += 2;
                        break;
					}elseif(is_vowel($ORIGINAL_STRING, $CURRENT_POS+1))
					{
						$META_KEY   .= 'L';
                        $CURRENT_POS += 2;
                        break;
                    }elseif($CURRENT_POS == 0){
						$META_KEY   .= 'L';
						$CURRENT_POS += 1;
					}else{
						$CURRENT_POS += 1;
					}
                break;
				
				case 'P':
                   if( substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1) == 'H' )
                    {
						$META_KEY   .= 'F';
                        $CURRENT_POS += 2;
                        break;
					}else
					{
						$META_KEY   .= 'P';
                        $CURRENT_POS += 1;
                        break;
                    }
                break;
				
				case 'M':
                   
						$META_KEY   .= 'M';
                        $CURRENT_POS += 1;
                        break;
					
                break;
				
            	case 'G':
                    switch ( substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1) )
                    {
						case 'H':
							if( !is_vowel($ORIGINAL_STRING, $CURRENT_POS+2)) {
								$META_KEY   .= 'G';
								$CURRENT_POS += 1;
							}
                        case 'E':
                        case 'I':
                            $META_KEY   .= 'J';
                            $CURRENT_POS += 2;
                        break;
						
						case 'G':
							if( substr($ORIGINAL_STRING, ($CURRENT_POS+2), 1) == 'E' || substr($ORIGINAL_STRING, ($CURRENT_POS+2), 1) == 'I' ) {
								$META_KEY   .= 'J';
								$CURRENT_POS += 2;
							}elseif(substr($ORIGINAL_STRING, ($CURRENT_POS+2), 1) == 'H'){
								$META_KEY   .= 'G';
								$CURRENT_POS += 2;
							}else{
								$META_KEY   .= 'G';
								$CURRENT_POS += 2;
							}
						break;

                        default:
                            $META_KEY   .= 'G';
                            $CURRENT_POS += 1;
                        break;
                    }
                break;
               
                case 'R':
                    if (($CURRENT_POS==0) || ($CURRENT_POS==strlen($ORIGINAL_STRING)-1))
                    {
                        $CURRENT_POS+=1;
                        $META_KEY   .= '2';
                        break;
                    }
                    elseif ( ($CURRENT_POS>0) && (is_vowel($ORIGINAL_STRING, $CURRENT_POS-1) && is_vowel($ORIGINAL_STRING, $CURRENT_POS+1)) )
                    {
                        $CURRENT_POS+=2;
                        $META_KEY   .= 'R';
						if(substr($ORIGINAL_STRING, ($CURRENT_POS), 1)=='S'){
							$LAST_CHAR = 'R';
						}
						
                        break;
                    }
                    /*
                     *    sen�o...
                     */
                    $CURRENT_POS += 1;
                    $META_KEY   .= 'R';
                break;

                case 'Z':
                             
                	if ($CURRENT_POS >= (strlen($ORIGINAL_STRING)-1) || substr($ORIGINAL_STRING, ($CURRENT_POS-1), 1)==' ')
                    {
                        $CURRENT_POS+=1;
                        $META_KEY   .= 'S';
                        break;
                    }
                    elseif (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1) == 'Z')
                    {
						if ($CURRENT_POS >= (strlen($ORIGINAL_STRING)-2) || substr($ORIGINAL_STRING, ($CURRENT_POS-2), 1)==' ')
						{
							$CURRENT_POS+=2;
							$META_KEY   .= 'S';
							break;
						}
                        //TODO
						$META_KEY   .= 'Z';
                        $CURRENT_POS += 2;
                        break;
                    }
                                        
                    /*
                     *    sen�o...
                     */
                    $CURRENT_POS += 1;
					//TODO
                    $META_KEY   .= 'Z';
                break;

                case 'N':
				
                    if (($CURRENT_POS>=(strlen($ORIGINAL_STRING)-1)))
                    {					
                        $META_KEY   .= 'M';
                        $CURRENT_POS += 1;
                        break;
                    }
                    elseif (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)=='N')
                    {
						if (($CURRENT_POS+1>=(strlen($ORIGINAL_STRING)-1)))
						{					
							$META_KEY   .= 'M';
							$CURRENT_POS += 2;
							break;
						}
                        $META_KEY   .= 'N';
                        $CURRENT_POS += 2;
                        break;
                    }else{					
						$META_KEY   .= 'N';
						$CURRENT_POS += 1;
						break;
					}

                case 'S':
					if (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)=='S'){
						$META_KEY .= 'S';
                        $CURRENT_POS += 2;
					}
					elseif (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)=='H'){
						$META_KEY .= 'X';
                        $CURRENT_POS += 2;
					}
					
					elseif(($CURRENT_POS > 0) && is_vowel($ORIGINAL_STRING, $CURRENT_POS-1) && is_vowel($ORIGINAL_STRING, $CURRENT_POS+1) && substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)!=''){
						if(!$LAST_CHAR == ""){
							$META_KEY .= 'S';
							$CURRENT_POS += 1;
							$LAST_CHAR = "";
						}else{							
							$META_KEY .= 'Z';	
							$CURRENT_POS += 1;
						}
					}
					elseif (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)=='C'){					
						switch( substr($ORIGINAL_STRING, ($CURRENT_POS+2), 1) ){
							case 'E':
							case 'I':
								$META_KEY   .= 'S';
								$CURRENT_POS += 2;
							break;
							
							case 'A':
							case 'O':
							case 'U':
								$META_KEY   .= 'SK';
								$CURRENT_POS += 2;
							break;
							
							case 'H':
								$META_KEY   .= 'X';
								$CURRENT_POS += 2;
							break;
							
							default:
								$META_KEY   .= 'S';
								$CURRENT_POS += 2;
							break;
						}						
					}
					else {
						$META_KEY .= 'S';
                        $CURRENT_POS += 1;
					}
					break;

                case 'X':
				
					if((substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)=='X') && is_vowel($ORIGINAL_STRING, ($CURRENT_POS+2))){
						$META_KEY .= 'Z';
                        $CURRENT_POS += 2;
						break;
					}
                    
                	if( (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1) == ' ') || ($CURRENT_POS == $STRING_LENGTH-1) ){
                		
                		$META_KEY .= 'X';
                        $CURRENT_POS += 1;
                	}
                	elseif ( ($CURRENT_POS > 0) && substr($ORIGINAL_STRING, ($CURRENT_POS-1), 1) == 'E'){
                		
                		$NEXT_CARACTER_AFTER_X = substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1);               		
                		if(is_vowel($ORIGINAL_STRING, ($CURRENT_POS+1))){
                			
                			/* exonerar, ex�rcito, executar, exemplo, exuberar, exame, ex�lio = ex + vowel */
                			if( ($CURRENT_POS-2) < 0 || substr($ORIGINAL_STRING, ($CURRENT_POS-2), 1)==' '){
                				//TODO
                				$META_KEY .= 'Z';
                				$CURRENT_POS += 1;                				
                			}
                			else{

                				switch( substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1) ){

                					/*
		                			 * palavras que encaixam neste trecho: m�xico, mexerica, mexer
		                			 */
                					case 'E':
			                        case 'I':
			                            $META_KEY   .= 'X';
			                            $CURRENT_POS += 2;
			                        break;

			                        /*
		                			 * palavras que encaixam neste trecho: anexar, sexo, convexo, nexo, circunflexo, sexual, alex, alexandre, texugo
		                			 */
			                        default:
			                        	$META_KEY   .= 'KS';
			                            $CURRENT_POS += 1;
			                        break;
                				}
                			}
                		}
						elseif($NEXT_CARACTER_AFTER_X == 'P' || $NEXT_CARACTER_AFTER_X == 'T' ){

                			/*
                			 * palavras que encaixam neste trecho: exce��o, exceto, expatriar, experimentar, extens�o, exterminar
                			 */
                			$META_KEY .= 'S';
                			$CURRENT_POS += 1;
                		}
                		elseif( $NEXT_CARACTER_AFTER_X == 'C'){

                			/*
                			 * palavras que encaixam neste trecho: exce��o, exceto, expatriar, experimentar, extens�o, exterminar
                			 */
                			$META_KEY .= 'S';
                			$CURRENT_POS += 2;
                		}
                		else{

                			/*
                			 * todas exce��es caem aqui
                			 */
                			$META_KEY .= 'KS';
                			$CURRENT_POS += 1;
                		}
                	}
                	/* parece que certas s�labas predecessoras do 'x' como 'ca' em 'abacaxi' provocam o som de 'CH' no 'x'.
					 * com exce��o do 'm', que � mais complexo.
					 */
                	elseif ( is_vowel($ORIGINAL_STRING, ($CURRENT_POS-1)) && (($CURRENT_POS-1) >= 0) ){
                		
                		if( ($CURRENT_POS-2) >= 0 ){
                			
                			$TWO_CARACTERS_PREVIOUS_X = substr($ORIGINAL_STRING, ($CURRENT_POS-2), 1);
                			
                			/*
		                     * palavras que encaixam neste trecho: faxina
		                     */
                			if($TWO_CARACTERS_PREVIOUS_X == 'F' && substr($ORIGINAL_STRING, ($CURRENT_POS-1), 1)=='A'){

                				$META_KEY .= 'X';
	                        	$CURRENT_POS += 1;
                			}
                			else{
                				
                				switch ($TWO_CARACTERS_PREVIOUS_X){
                					
                					/*
                					 * encontros voc�licos
                					 */
                					case 'A': case 'E':case 'I':case 'O':case 'U':
                					case 'C': /* palavras que encaixam neste trecho: caixa, trouxe */
									case 'K':
									case 'G': /* palavras que encaixam neste trecho: gaxeta */
									case 'L': /* palavras que encaixam neste trecho: laxante, lixa, lixo */
									case 'R': /* palavras que encaixam neste trecho: roxo, bruxa */
									case 'X': /* palavras que encaixam neste trecho: xaxim */

										/* palavras que tamb�m se encaixam neste trecho: abacaxi, abaixar, frouxo, guaxo, Teixeira */
                						$META_KEY .= 'X';
	                        			$CURRENT_POS += 1;
                					break;
                					
                					default:
                						$META_KEY .= 'KS';
	                        			$CURRENT_POS += 1;
                					break;
                				}
                			}                			
                		}
                		else{
                			
		                	/*
		                     * palavras que encaixam neste trecho: 't�xi', 'axioma', 'axila', 't�xico', fixar, fixo, mon�xido, �xido, maxilar, m�ximo
		                     */
	                        $META_KEY .= 'KS';
	                        $CURRENT_POS += 1;
                		}
                	}
		            else{

		            	/*
		            	 * palavras que encaixam neste trecho: enxame, enxada e todas as outras que possivelmente n�o foram mapeadas
		            	 */
		            	$META_KEY .= 'X';
                        $CURRENT_POS += 1;
                    }                	
                break;
                
                case 'C':
                    /*
                     *    caso especial 'cinema', 'cereja'
                     */
                    if ( string_at($ORIGINAL_STRING, $CURRENT_POS, 2,array('CE','CI')) )
                    {
                        $META_KEY   .= 'S';
                        $CURRENT_POS += 2;
                    }
                    elseif( (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)=='H'))
                    {
						if( (substr($ORIGINAL_STRING, ($CURRENT_POS+2), 1)=='R'))
						{
							$META_KEY   .= 'K';
							$CURRENT_POS += 2;
						}else{
							$META_KEY   .= 'X';
							$CURRENT_POS += 2;
						}
                    }
            		elseif( (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)=='Q') || (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)=='K'))
                    {
                        /* Jacques: n�o fazer nada. Deixar o 'Q' cuidar disso
	  					 */
                    	$CURRENT_POS += 1;
                    	break;
                    }elseif( (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)=='K'))
                    {
                    	$CURRENT_POS += 1;
                    	break;
                    }
					elseif( (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)=='C'))
                    {
						if ( string_at($ORIGINAL_STRING, $CURRENT_POS+1, 2,array('CE','CI')) )
						{
							$META_KEY   .= 'S';
							$CURRENT_POS += 2;
						}elseif( (substr($ORIGINAL_STRING, ($CURRENT_POS+2), 1)=='H'))
						{
							$META_KEY   .= 'X';
							$CURRENT_POS += 2;
						}else{
							$META_KEY   .= 'K';
							$CURRENT_POS += 2;
						}
                    }
                    else
                    {
						
                        $META_KEY   .= 'K';
                        $CURRENT_POS += 1;
                    }
                    break;

                /*
                 *    como a letra 'h' � silenciosa no portugu�s, vamos colocar
                 *    a chave meta como a vogal logo ap�s a letra 'h'
                 */
                case 'H':	
					if($CURRENT_POS == 0){
						if ( is_vowel($ORIGINAL_STRING, $CURRENT_POS + 1) )
						{
							$META_KEY .= $ORIGINAL_STRING[$CURRENT_POS + 1];
							$CURRENT_POS += 2;
							IF(substr($ORIGINAL_STRING, $CURRENT_POS,1) == 'S'){
								$LAST_CHAR = 'H';
							}
							break;
						}
					}
					$CURRENT_POS += 1;				
					break;

                case 'Q':
                   // if (substr($ORIGINAL_STRING, $CURRENT_POS + 1,1) == 'U')
                   // {
                      // $CURRENT_POS += 2;
                   // }
                   // else
                   // {
                      // $CURRENT_POS += 1;
                   // }

                   $META_KEY   .= 'K';
				   $CURRENT_POS += 1;
                   break;

                case 'W':
                    if (is_vowel($ORIGINAL_STRING, $CURRENT_POS + 1))
                    {
                        $META_KEY   .= 'V';
                        $CURRENT_POS += 2;
                    }
                    else
                    {   
                     	$CURRENT_POS += 1;
                    }
                    break;

                case ' ':
               		$META_KEY   .= '|';
   	           		$CURRENT_POS += 1;
                    break;

                default:
                    $CURRENT_POS += 1;
            }
        }
    }

    /*
     *    corta os caracteres em branco
     */
    $META_KEY = trim($META_KEY);

    /*
     *    retorna a chave mataf�nica
     */
    return $META_KEY;
}

function string_at($STRING, $START, $STRING_LENGTH, $LIST)
{
    if ( ($START <0) || ($START >= strlen($STRING)) )
    {
        return 0;
    }
    for ( $I=0; $I<count($LIST); $I++)
    {
        if ( $LIST[$I] == substr($STRING, $START, $STRING_LENGTH))
        {
            return 1;
        }
    }
    return 0;
}

function is_vowel($string, $pos)
{
    return preg_match('/^[AEIOUY]+$/', substr($string, $pos, 1));
}


?>