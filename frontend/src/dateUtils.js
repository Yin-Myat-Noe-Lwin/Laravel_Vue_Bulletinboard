export const formatDate = (datetime) => {

  const date = new Date(datetime);

  return date.toLocaleDateString();

};
