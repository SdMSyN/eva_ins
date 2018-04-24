Tablas nuevas: (est_exa_result_info, est_exa_result_preguntas, est_exa_respuestas_tmp, est_exa_tiempos);

est_exa_respuestas_tmp, cambio en columna 'respuesta'

/*17/10/2016*/
ALTER TABLE `exa_info_asig` ADD `mostrar_resultado` DATETIME NULL AFTER `fin`;

/*18-10-2016 Avisos - Notificaciones */
Tablas nuevas: "aviso_info", "aviso_asig_alum", "aviso_asig_tutor", "aviso_tipo";

/*03-11-2016*/
Tabla nueva: "usuarios_escuelas_secretarias"
Modificar tabla banco_preguntas añadir campo activo
UPDATE `banco_preguntas` SET `activo`='1' WHERE 1