import { ref } from 'vue';

export const testComponent = () => {
  const message = ref('');

  const changeMessage = (newMessage) => {
    console.log('run change message');
    message.value = newMessage;
  }
  
  return {message, changeMessage};
};