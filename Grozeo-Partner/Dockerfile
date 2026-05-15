FROM mcr.microsoft.com/dotnet/sdk:8.0-alpine AS build
WORKDIR /src

COPY RetalineProAgent.Net8/RetalineProAgent.csproj RetalineProAgent.Net8/
RUN dotnet restore RetalineProAgent.Net8/RetalineProAgent.csproj

COPY RetalineProAgent.Net8/ RetalineProAgent.Net8/
RUN dotnet publish RetalineProAgent.Net8/RetalineProAgent.csproj -c Release -o /app/publish --no-restore

FROM mcr.microsoft.com/dotnet/aspnet:8.0-alpine AS runtime
WORKDIR /app

RUN apk add --no-cache curl

COPY --from=build /app/publish .

ENV ASPNETCORE_ENVIRONMENT=Production

EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=10s --start-period=15s --retries=3 \
    CMD curl -f http://localhost:${PORT:-8080}/health || exit 1

ENTRYPOINT ["sh", "-c", "exec dotnet RetalineProAgent.dll --urls http://+:${PORT:-8080}"]
