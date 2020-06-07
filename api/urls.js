import { list, create } from "../lib/urls";

export default async (req, res) => {
  const { query, method, body } = req;

  switch (method) {
    case "GET":
      list(query)
        .then(results => res.status(200).json(results))
        .catch(err => res.json(err));
      break;
    case "POST":
      create(body)
        .then(results => res.status(201).json(results))
        .catch(err => res.json(err));
      break;
    default:
      res.setHeader("Allow", ["GET", "POST"]);
      res.status(405).end(`Method ${method} not allowed`);
  }
};
