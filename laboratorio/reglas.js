/* Funciones pequeñas y puras: material de aprendizaje, no autorización de servidor. */
(function (root) {
  'use strict';
  function validarContacto(valor) {
    const texto = String(valor || '').trim();
    return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(texto);
  }
  function reservarLocal(sesion, tipo, ocupadas) {
    if (!sesion || !sesion.abierta) return {ok:false, codigo:'SESION_CERRADA'};
    if (sesion.servicio === 'taller' && tipo === 'menor_representado') return {ok:false,codigo:'TALLER_SOLO_ADULTOS_DEMO'};
    if (ocupadas >= sesion.capacidad) return {ok:false,codigo:'SIN_PLAZA'};
    return {ok:true,codigo:'RESERVADA',ocupadas:ocupadas+1};
  }
  function validarMedida(valor, tipo) {
    if (valor === null || String(valor).trim() === '') return {ok:true,valor:null};
    const texto = String(valor).trim();
    if (!/^-?\d{1,6}(\.\d{1,3})?$/.test(texto)) return {ok:false,error:'FORMATO'};
    const numero = Number(texto);
    if (!Number.isFinite(numero)) return {ok:false,error:'FORMATO'};
    if (tipo === 'porcentaje' && (numero < 0 || numero > 100)) return {ok:false,error:'RANGO_DEMO'};
    if (tipo === 'eva' && (numero < 0 || numero > 10)) return {ok:false,error:'RANGO_DEMO'};
    return {ok:true,valor:numero};
  }
  function celdaCSV(valor, esNumero) {
    if (valor === null || valor === undefined) return '';
    let texto = String(valor);
    if (esNumero) {
      if (!Number.isFinite(Number(valor)) || texto.trim() === '') throw new Error('Número no válido');
    } else if (/^[\s]*[=+\-@]/.test(texto)) texto = "'" + texto;
    return '"' + texto.replace(/"/g,'""') + '"';
  }
  function puedeRegistrarResultado(usuario, documento, cuenta) {
    const tipoNecesario = usuario.tipo === 'adulto' ? 'ADULTO-DEMO' : 'MENOR-DEMO';
    return usuario.estado === 'confirmado' && cuenta.rol === 'profesional'
      && cuenta.centros.includes(documento.centro) && documento.usuario === usuario.id
      && documento.estado === 'firmado' && documento.tipo === tipoNecesario;
  }
  const api = {validarContacto,reservarLocal,validarMedida,celdaCSV,puedeRegistrarResultado};
  if (typeof module !== 'undefined' && module.exports) module.exports = api;
  else root.ReglasDemo = api;
})(typeof globalThis !== 'undefined' ? globalThis : this);
