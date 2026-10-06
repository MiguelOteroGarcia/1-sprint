'use strict';
const sesiones={S001:{capacidad:1,abierta:true,servicio:'individual'},S002:{capacidad:3,abierta:true,servicio:'taller'},S003:{capacidad:1,abierta:true,servicio:'individual'}};
let ocupacion={S001:0,S002:0,S003:1};
const $=id=>document.getElementById(id);
$('reserva').addEventListener('submit',event=>{
  event.preventDefault();
  const contacto=$('contacto');const correcto=ReglasDemo.validarContacto(contacto.value);
  contacto.setAttribute('aria-invalid',String(!correcto));$('contacto-error').textContent=correcto?'':'Escribe un correo ficticio con nombre y dominio.';
  if(!correcto){$('resultado').textContent='Revisa el contacto. Los demás campos se conservan.';contacto.focus();return;}
  if(!$('nombre').value.trim()){$('resultado').textContent='Escribe un nombre inventado.';$('nombre').focus();return;}
  const id=$('sesion').value;const r=ReglasDemo.reservarLocal(sesiones[id],$('tipo').value,ocupacion[id]);
  if(r.ok)ocupacion[id]=r.ocupadas;
  const mensajes={RESERVADA:'Reserva local de ejemplo creada. No se ha enviado información.',SIN_PLAZA:'Ya no quedan plazas. Elige otra sesión; tus datos se conservan.',TALLER_SOLO_ADULTOS_DEMO:'Esta demo solo admite adultos en talleres. No se ha ocupado ninguna plaza.',SESION_CERRADA:'La sesión no admite reservas.'};
  $('resultado').textContent=mensajes[r.codigo];$('respuesta').textContent=JSON.stringify({simulacion:true,sesion:id,...r},null,2);
});
$('reiniciar').addEventListener('click',()=>{ocupacion={S001:0,S002:0,S003:1};$('resultado').textContent='Casos reiniciados; S001 vuelve a tener una plaza.';$('respuesta').textContent='Sin solicitudes.';});
const canvas=$('lienzo');const ctx=canvas.getContext('2d');let trazos=[];let actual=null;
function punto(e){const r=canvas.getBoundingClientRect();return [(e.clientX-r.left)/r.width,(e.clientY-r.top)/r.height];}
function dibujar(){ctx.clearRect(0,0,canvas.width,canvas.height);ctx.strokeStyle='#123d49';ctx.lineWidth=3;ctx.lineCap='round';for(const trazo of trazos){ctx.beginPath();trazo.forEach((p,i)=>i?ctx.lineTo(p[0]*canvas.width,p[1]*canvas.height):ctx.moveTo(p[0]*canvas.width,p[1]*canvas.height));ctx.stroke();}}
canvas.addEventListener('pointerdown',e=>{if(actual)return;canvas.setPointerCapture(e.pointerId);actual=[punto(e)];trazos.push(actual);e.preventDefault();});
canvas.addEventListener('pointermove',e=>{if(!actual)return;actual.push(punto(e));dibujar();});
function terminar(){actual=null;$('estado-firma').textContent=trazos.some(t=>t.length>1)?'Trazo de ejemplo presente. Falta la validación del servidor.':'Lienzo vacío o sin recorrido suficiente.';}
canvas.addEventListener('pointerup',terminar);canvas.addEventListener('pointercancel',terminar);
$('borrar').addEventListener('click',()=>{trazos=[];actual=null;dibujar();$('estado-firma').textContent='Lienzo vacío. Aún no hay ningún trazo.';});
$('comprobar').addEventListener('click',()=>{$('estado-firma').textContent=trazos.some(t=>t.length>1)?'Comprobación local: hay trazo. No se ha guardado ni firmado un documento.':'Comprobación local: firma vacía; dibuja antes de continuar.';});
window.addEventListener('resize',dibujar);
