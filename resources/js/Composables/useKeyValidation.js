export const allowOnlyInteger = (event) => {
    // Permite teclas de navegación sin restricciones
    const allowedKeys = ['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Tab', 'Backspace', 'Delete', 'Enter'];
    if (allowedKeys.includes(event.key)) return;

    // Crea una copia del valor actual del input, incluyendo el nuevo carácter
    const proposedValue = event.target.value + event.key;
    // Verifica si el valor propuesto es un número decimal válido
    const isInteger = /^-?\d*$/.test(proposedValue);

    if (!isInteger) {
        // Previene la adición del carácter si el resultado no es un número decimal
        event.preventDefault();
    }
}
