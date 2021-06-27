<?php

/*
* Azərbaycan dili üçün ahəng qanunu
* Məcidov Abbas (2015, Gəncə)
* Ahəng qanunu - sözdəki saitlərinin bir-birini izləməsi ilə müəyyən edilən qramatik normadır. 
* Ətraflı məlumat üçün az.wikipedia.org/wiki/Ahəng_qanunu
* Lisenziya: GPL 
*/

//İncə saitlər
const I = 'eəöiü';

//Qalın saitlər
const Q = 'aıou';

/* Şəkilçi açarları */
const A = 'ıiuü';
const B = 'aə';

/* Funksiya sətir tipində olan qiymət qaytarır */
function VowelHarmony($word, $suffix)
{

	//Arqument massiv olarsa
	if(is_array($word))
		$word = implode(", ", $word);

	//Sonuncu vergülün "və" bağlayıcı ilə əvəz edilməsi
	$word = preg_replace("/(.*),\s(.*)$/mui", "$1 və $2", $word);

	//Sözdəki sait səslərin müəyyən edilməsi
	preg_match_all("/[".I.Q."]/mui", $word, $matches);

	//Şəkilçidəki sait səslərin müəyyən edilməsi
	preg_match_all("/[".I.Q."]/mui", $suffix, $matches_);

	//Sözdəki sonuncu saitin müəyyən edilməsi
	$lastVowelWord = end($matches[0]);

	//Sözdəki sondan bir pillə yuxarı saitin müəyyən edilməsi
	$lastVowelWord_ = $matches[0][count($matches[0]) - 2];

	//Sözdəki sonuncu hərfin müəyyən edilməsi
	$lastLetterWord = mb_substr($word, -1);

	//Şəkilçidəki ilk hərfin müyyən edilməsi
	$firstLetterSuffix = mb_substr($suffix, 0, 1);

	//Şəkilçidəki bütün saitləri özündə ehtiva edən massiv (*1)
	$suffixLetter = $matches_[0];

	//Ahəng qanuna uyğun şəkilçi özündə saxlayan dəyişən
	$vw = "";

	//*1 massivinin iterasiyası
	foreach ($suffixLetter as $key => $value) 
	{

		/*
			Hər bir massiv elementinin yoxlanılması və 
			ahəng qanununa müvafiq olaraq saitlərin uyğunlaşdırılması
		*/
			
		if(
			(preg_match("/[".A."]/mui", $lastVowelWord) && preg_match("/[".A."]/mui", $value)) || 
			(preg_match("/[".B."]/mui", $lastVowelWord) && preg_match("/[".B."]/mui", $value)))
				$vw = $lastVowelWord;

		//Sözdəki sonuncu saitin qalın sait olmasının yoxlanılması
		else if(preg_match("/[".Q."]/mui", $lastVowelWord))
		{

			if($lastVowelWord == "a" && preg_match("/[".A."]/mui", $value)) $vw = "ı";
			else if(preg_match("/[ıu]/mui", $lastVowelWord) && preg_match("/[".B."]/mui", $value)) $vw = "a";

		}
		
		else if(preg_match("/[".I."]/mui", $lastVowelWord))
		{
			if($lastVowelWord == "ə" && preg_match("/[".A."]/mui", $value)) $vw = "i";
			else if(preg_match("/[iü]/mui", $lastVowelWord) && preg_match("/[".B."]/mui", $value)) $vw = "ə";
		}

		if(count($suffixLetter) > 1)
			$vw = $vw == "ü" ? "i" : ($vw == "u" ? "ı" : $vw);

		if(!empty($vw))
			$suffix = preg_replace("/{$value}/mui", $vw, $suffix);

	}


	/* Şəkilçi əlavələrinin tətbiqi */

	if(preg_match("/[".I.Q."]/mui", $lastLetterWord) && preg_match("/[".B."]/mui", $firstLetterSuffix))
		$suffix = "y" . $suffix;

	if(preg_match("/[".I.Q."]/mui", $lastLetterWord) && preg_match("/[".A."]/mui", $firstLetterSuffix))
		$suffix = "n" . $suffix;

	if(preg_match("/[q]/mui", $lastLetterWord))
		$word = preg_replace("/q$/mui", "ğ", $word);

	if(preg_match("/[k]/mui", $lastLetterWord))
		$word = preg_replace("/k$/mui", "y", $word);


	return $word.$suffix;

}


/* İstifadəsi */

echo VowelHarmony("Əli", "ün")." cəmi 15 xalı var. ";
//Əli, Mikayıl və Abbasın cəmi 15 xalı var

echo VowelHarmony(["Əli", "Mikayıl", "Abbas"], "ün")." cəmi 15 xalı var. ";
//Əli, Mikayıl və Abbasın cəmi 15 xalı var

?>