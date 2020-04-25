const fetch = require("node-fetch");
const invokeUrl = process.env.REACT_APP_INVOKE_URL;

module.exports = async (req, res) => {
  const { id } = req.query;

  return fetch(`${invokeUrl}/urls/${id}`, { redirect: "manual" }).then(
    result => {
      res.setHeader("Location", result.headers.get("location"));
      res.setHeader("Content-Type", "text/html");
      res.status(302).send();
    }
  );
};
