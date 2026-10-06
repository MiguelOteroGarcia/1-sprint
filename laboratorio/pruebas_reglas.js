'use strict';
const assert=require('node:assert/strict');const r=require('./reglas.js');let total=0;
function prueba(nombre,fn){fn();total++;process.stdout.write('OK '+nombre+'\n');}
prueba('contacto inválido no pasa',()=>assert.equal(r.validarContacto('sin-arroba'),false));
prueba('contacto ficticio válido',()=>assert.equal(r.validarContacto('persona@example.test'),true));
prueba('última plaza local',()=>{const s={abierta:true,capacidad:1,servicio:'individual'};assert.equal(r.reservarLocal(s,'adulto',0).ok,true);assert.equal(r.reservarLocal(s,'adulto',1).codigo,'SIN_PLAZA');});
prueba('menor en taller rechazado sin incrementar',()=>assert.deepEqual(r.reservarLocal({abierta:true,capacidad:3,servicio:'taller'},'menor_representado',0),{ok:false,codigo:'TALLER_SOLO_ADULTOS_DEMO'}));
prueba('sesión cerrada',()=>assert.equal(r.reservarLocal({abierta:false},'adulto',0).codigo,'SESION_CERRADA'));
prueba('nulo y cero distintos',()=>{assert.equal(r.validarMedida('','general').valor,null);assert.equal(r.validarMedida('0','general').valor,0);});
prueba('negativo admitido para T/Z',()=>assert.equal(r.validarMedida('-1.250','general').valor,-1.25));
prueba('porcentajes y EVA en límites',()=>{assert.equal(r.validarMedida('100','porcentaje').ok,true);assert.equal(r.validarMedida('101','porcentaje').ok,false);assert.equal(r.validarMedida('10.1','eva').ok,false);});
prueba('NaN e infinito rechazados',()=>{assert.equal(r.validarMedida('NaN').ok,false);assert.equal(r.validarMedida('Infinity').ok,false);});
prueba('CSV texto neutralizado y número negativo conservado',()=>{assert.equal(r.celdaCSV('=1+1',false),'"\'=1+1"');assert.equal(r.celdaCSV(-1.25,true),'"-1.25"');assert.equal(r.celdaCSV('a,"b"',false),'"a,""b"""');});
prueba('documento de taller no habilita pruebas',()=>{const u={id:'U1',estado:'confirmado',tipo:'adulto'};const d={usuario:'U1',centro:'C1',estado:'firmado',tipo:'TALLER-DEMO'};assert.equal(r.puedeRegistrarResultado(u,d,{rol:'profesional',centros:['C1']}),false);d.tipo='ADULTO-DEMO';assert.equal(r.puedeRegistrarResultado(u,d,{rol:'profesional',centros:['C1']}),true);assert.equal(r.puedeRegistrarResultado(u,d,{rol:'recepcion',centros:['C1']}),false);assert.equal(r.puedeRegistrarResultado(u,d,{rol:'profesional',centros:['C2']}),false);});
process.stdout.write(total+' pruebas de funciones locales. No acreditan backend ni concurrencia real.\n');
