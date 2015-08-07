<?php
/*
 *	pt_metaphone() "Portuguese Metaphone"
 *	version 1.0
 *
 *	Essa função pega uma palavra em português do Brasil e a retorna
 *	em uma chave metafônica.
 *
 *	Copyright (C) 2008		Prefeitura Municipal de Várzea Paulista
 *							<metaphone@varzeapaulista.sp.gov.br>
 *
 *	Histórico:
 *	2008-05-20		Versão 1.0
 *					Initial Release
 *
 *	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 *
 *	RECONHECIMENTO :
 *
 *	Essa função foi adaptada de uma função  chamada spanish_metaphone
 *	do Israel J. Sustaita. O código fonte original pode ser obtido em
 *	http://www.geocities.com/isloera/spanish_methaphone.txt (baseada
 *	na versão original do DoubleMetaphone em inglês de Geoff Caplan).
 *
 *	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 *
 *	AGRADECIMENTOS :
 *	Sílvia e Thaiza pela ajuda com a língua portuguesa
 *
 *	EQUIPE DE DESENVOLVIMENTO :
 * 		Rodrigo Domingos Pinto Lotierzo
 *		Giovanni dos Reis Nunes
 *
 * 		Estagiários:
 * 		  Caio Varlei Righi Schleich
 *		  Diego Jorge de Souza
 *		  Sueli Silvestre da Silva
 *
 *	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 *
 *	Funcionamento:
 *
 *	1.	Esta função recebe a string contendo o nome, palavra ou frase
 *		a ser criada a chave e retorna essa chave.
 *
 *	2.	Receber a 'string', a primeira coisa a ser feita é substituir
 *		os dígrafos e encontros consonantais pelas letras que corres-
 *		pondem aos seus sons.
 *
 *	3.	Os dígrafos "LH", "NH" e o encontro consonantal "RR" são con-
 *		vertidos em números para facilitar a interpretação.
 *
 *	4.	Os dígrafos "CH" e "PH" (questões históricas), o encontro con-
 *		sonantal "SC" e o "Ç" são convertidos em seus fonemas corres-
 *		pondentes.
 *
 *	5.	Os acentos são removidos das vogais.
 *
 *	6.	Letras cujos fonemas não se alteram não são mexidas ("B", "V",
 *		"P",etc...).
 *
 * 	7.	Outras letras como "G" e "X" são tratadas de acordo com casos
 *		específicos.
 *
 *	Histórico:
 *	2011-09-05		Versão 1.1
 *
 *	EQUIPE DE DESENVOLVIMENTO :
 * 		Fabricio Meireles Monteiro   -   S2IT Solutions
 *
 *
 *
 *	Ajustes:
 *
 *	1.	Foram realizados ajustes para adequar a nova versão disponibilizada.
 * *	Histórico:
 *	2011-01-18		Versão 1.2
 *
 *	EQUIPE DE DESENVOLVIMENTO :
 * 		Thiago Henriques Alves Correa   -   S2IT Solutions
 *
 *
 *
 *	Ajustes:
 *
 *	1.	Novos ajustes para adequar a nova versão disponibilizada.
 */

function portuguese_metaphone($STRING,$LENGTH=50)
{
    /*
     *    inicializa a chave metafônica
     */
    $META_KEY = "";

    /*
     *    configura o tamanho máximo da chave metafônica
     */
    $KEY_LENGTH = (int)$LENGTH;


    /*
     *    coloca a posição no começo
     */
    $CURRENT_POS = (int)0;

    /*
     *    recupera o tamanho máximo da string
     */
    $STRING_LENGTH = (int)strlen($STRING);

    /*
     *    configura o final da string
     */
    $END_OF_STRING_POS=$STRING_LENGTH-1;
    $ORIGINAL_STRING=$STRING."    ";

    /*
     *    vamos repor alguns caracteres portugueses facilmente confundidos, 
     *    substituindo-os por números. Não confundir com os 
     *    encontros consonantais (RR), 
     *    dígrafos (LH, NH) e o
     *    C-cedilha:
     *
     *    'LH' to '1'
     *    'RR' to '2'
     *    'NH' to '3'
     *    'Ç'  to 'SS'
     *    'CH' to 'X'
     */
    $ORIGINAL_STRING = str_replace('[1|2|3|4|5|6|7|8|9|0]',' ',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('[ã|á|â]','A',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('[é|ê]','E',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('[í|y]','I',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('[õ|ó|ô]','O',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('[ú|ü]','U',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('ç','S',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('Ç','S',$ORIGINAL_STRING);
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
     *    a correção do TH e do PH por conta dos nomes próprios:
     *    -> "schiffer", "theodora", "ophelia"
     */
    $ORIGINAL_STRING = str_replace('TH','T',$ORIGINAL_STRING);
    $ORIGINAL_STRING = str_replace('PH','F',$ORIGINAL_STRING);

    /*
     *    remove espaços extras
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
         *    se é uma vogal e faz parte do começo da string,
         *    coloque-a como parte da metachave
         */
               
        if( (is_vowel($ORIGINAL_STRING, $CURRENT_POS)) && ( ($CURRENT_POS == 0) || (string_at($ORIGINAL_STRING, $CURRENT_POS-1, 1," "))) )
        {
            $META_KEY .= $CURRENT_CHAR;
            $CURRENT_POS += 1;
        }
        /*
         * procurar por consoantes que tem um único som, ou que que já foram substituídas ou soam parecido, como 'Ç' para 'S' e 'NH' para '3'
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
             *    senão incrementa em 1
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
                     *    senão...
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
                     *    senão...
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
                			
                			/* exonerar, exército, executar, exemplo, exuberar, exame, exílio = ex + vowel */
                			if( ($CURRENT_POS-2) < 0 || substr($ORIGINAL_STRING, ($CURRENT_POS-2), 1)==' '){
                				//TODO
                				$META_KEY .= 'Z';
                				$CURRENT_POS += 1;                				
                			}
                			else{

                				switch( substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1) ){

                					/*
		                			 * palavras que encaixam neste trecho: méxico, mexerica, mexer
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
                			 * palavras que encaixam neste trecho: exceção, exceto, expatriar, experimentar, extensão, exterminar
                			 */
                			$META_KEY .= 'S';
                			$CURRENT_POS += 1;
                		}
                		elseif( $NEXT_CARACTER_AFTER_X == 'C'){

                			/*
                			 * palavras que encaixam neste trecho: exceção, exceto, expatriar, experimentar, extensão, exterminar
                			 */
                			$META_KEY .= 'S';
                			$CURRENT_POS += 2;
                		}
                		else{

                			/*
                			 * todas exceções caem aqui
                			 */
                			$META_KEY .= 'KS';
                			$CURRENT_POS += 1;
                		}
                	}
                	/* parece que certas sílabas predecessoras do 'x' como 'ca' em 'abacaxi' provocam o som de 'CH' no 'x'.
					 * com exceção do 'm', que é mais complexo.
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
                					 * encontros vocálicos
                					 */
                					case 'A': case 'E':case 'I':case 'O':case 'U':
                					case 'C': /* palavras que encaixam neste trecho: caixa, trouxe */
									case 'K':
									case 'G': /* palavras que encaixam neste trecho: gaxeta */
									case 'L': /* palavras que encaixam neste trecho: laxante, lixa, lixo */
									case 'R': /* palavras que encaixam neste trecho: roxo, bruxa */
									case 'X': /* palavras que encaixam neste trecho: xaxim */

										/* palavras que também se encaixam neste trecho: abacaxi, abaixar, frouxo, guaxo, Teixeira */
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
		                     * palavras que encaixam neste trecho: 'táxi', 'axioma', 'axila', 'tóxico', fixar, fixo, monóxido, óxido, maxilar, máximo
		                     */
	                        $META_KEY .= 'KS';
	                        $CURRENT_POS += 1;
                		}
                	}
		            else{

		            	/*
		            	 * palavras que encaixam neste trecho: enxame, enxada e todas as outras que possivelmente não foram mapeadas
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
                        /* Jacques: não fazer nada. Deixar o 'Q' cuidar disso
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
                 *    como a letra 'h' é silenciosa no português, vamos colocar
                 *    a chave meta como a vogal logo após a letra 'h'
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
     *    retorna a chave matafônica
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