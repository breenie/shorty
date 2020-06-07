import { visit } from "../lib/urls";

export default async (req, res) => {
  const { id } = req.query;

  return visit(id)
    .then(result => {
      res.setHeader("Location", result.url.S);
      res.setHeader("Content-Type", "text/html");
      res.status(302).send();
    })
    .catch(err => {
      let response = { status: 500, message: "Internal server error" };
      if ("ConditionalCheckFailedException" === err.code) {
        response.status = 404;
        response.message = "Cannot find URL ${id}";
      }
      // TODO this should be text/html
      res.status(response.status).json(response);
    });
};
