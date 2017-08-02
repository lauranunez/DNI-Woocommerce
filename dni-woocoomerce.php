<?php
/**
Plugin Name: DNI
Plugin URI: https://tallerdewpress.com
Description: Añade campo DNI en Woocoomerce
Version: 1.0
Author: TallerdeWpress
Author URI: 
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

/*
* AÑADIR CAMPO NIF/CIF EN EL FORMULARIO DE PAGO
*/
function woo_custom_field_checkout($checkout) {
  echo '<div id="additional_checkout_field">';
  woocommerce_form_field( 'nif', array( // Identificador del campo 
    'type'          => 'text',
    'class'         => array('my-field-class form-row-wide'),
    'required'      => true,            // ¿El campo es obligatorio 'true' o 'false'?
    'label'       => __('NIF'),   // Nombre del campo 
    'placeholder'   => __('Ej: 12345678X'), // Texto de apoyo que se muestra dentro del campo
  ), $checkout->get_value( 'nif' ));    // Identificador del campo 
  echo '</div>'; 
}
add_action( 'woocommerce_after_checkout_billing_form', 'woo_custom_field_checkout' );
/*
* INCLUYE NIF/CIF EN LOS DETALLES DEL PEDIDO CON EL NUEVO CAMPO
*/
function woo_custom_field_checkout_update_order($order_id) {
  if ( ! empty( $_POST['nif'] ) ) {
    update_post_meta( $order_id, 'NIF', sanitize_text_field( $_POST['nif'] ) );
  }
}
add_action( 'woocommerce_checkout_update_order_meta', 'woo_custom_field_checkout_update_order' );
/*
* MUESTRA EL VALOR DEL CAMPO NIF/CIF LA PÁGINA DE MODIFICACIÓN DEL PEDIDO
*/
function woo_custom_field_checkout_edit_order($order){
  echo '<p><strong>'.__('NIF').':</strong> ' . get_post_meta( $order->id, 'NIF', true ) . '</p>';
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'woo_custom_field_checkout_edit_order', 10, 1 );
/*
* INCLUYE EL CAMPO NIF/CIF EN EL CORREO ELECTRÓNICO DE AVISO A TU CLIENTE
*/
function woo_custom_field_checkout_email($keys) {
  $keys[] = 'NIF';
  return $keys;
}
add_filter('woocommerce_email_order_meta_keys', 'woo_custom_field_checkout_email');
