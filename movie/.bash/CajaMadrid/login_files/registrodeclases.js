  /*------------------------------------------------------------------------------*/
/* 
 Uso:
	Object.registraClase(elemento(s), clase [, parametro(s)]);

 Parametros
	- elemento(s): Obligatorio. Se puede indicar bien el objeto DOM sobre el que 
		se quiere aplicar la clase o una una cadena de texto que puede tener 
		tres formatos:
		1 -	"nombreEtiqueta" aplica la clase a todos los elementos 
			con el mismo nombre de etiqueta en la página. Ejemplo "input".
		2 -	"#id" aplica la clase al elemento cuyo id coincida con 
el indicado. Ejemplo "#idZona".
		3 - 	"%nombre" se encarga de aplicar la clase a todos 
			aquellos elementos cuyo name coincida con el especificado. 
Ejemplo "%nombreZona".
	- clase : Obligatorio. La función que representa una clase estandar ECMA3.
	- parametro (s): Un array de elementos que se deben de pasar al constructor 
		de la clase. Si no se indican el constructor será invocado
		 sin parámetros.
*/
/*------------------------------------------------------------------------------*/

Object.registraClase = function(tag, clase, args){
	// Se mantienen referencias para volver a aplicar la clase 
	// mapeando sombre document.createElement
	if (!this._tags){
		this._tags = new Array();
	}

	// Variable donde se almacenarán los objetos sobre los que 
	// se quiere aplicar la clase
	var tags;
	
	if (typeof tag == "string"){
		// Si se indica el elemento a aplicar la clase mediante un string hay que 
		// comprobar que el navegador permite más que manejo de DOM básico
		if (!document.getElementsByTagName || !document.getElementById){
			return false;
		}

		// Se comprueba si el nombre del elemento se ha indicado mediante el 
		// id, el name o el tagname
		if (tag.charAt(0) != "#" && tag.charAt(0) != "%"){
			this._tags[tag] = {clase:clase,args:args};
		}

		if (!args){
			args = [];
		
		}	

		// Si comienza por # es que se ha indicado mediante el id	
		if (tag.charAt(0) == "#"){
			tags = [document.getElementById(tag.substr(1))];
		} else {
			// Si comienza por % es que se ha indicado mediante el name
			if (tag.charAt(0) == "%"){
				tags = document.getElementsByName(tag.substr(1));
			} else {
				// Se ha indicado mediante el tagname
				tags =  document.getElementsByTagName(tag);		
			}

		}
	
	} else {
		// Directamente se ha pasado el objeto
		tags = [tag];
	}
	
	// Se aplica la clase a los elementos indicados
	var obj;
	for (var i = 0; i < tags.length; i++){
		obj = tags[i];
		// Se copian los métodos del prototype de la clase
		for (var p in clase.prototype){
			obj[p] = clase.prototype[p];
		}
		// Se reinstancia
		if (clase.apply){
			clase.apply(obj);
		} else {
			// Navegador viejo, no aplicable
			obj.__RCinit = clase;
			obj.__RCinit(args[0], args[1], args[2], args[3], args[4], args[5], args[6], args[7], args[8], args[9]);
		}
	}

	return true;
}